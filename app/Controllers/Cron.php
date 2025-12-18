<?php

namespace App\Controllers;
use CodeIgniter\Controller;

use App\Models\Admin\GameMappingModel;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\LeaderboardModel;
use App\Models\Admin\DailyCounterModel;

class Cron extends Controller
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();

    }
    //update leaderboard,daily game counter, game mapping table status, runs every day at 1:00 AM
    public function updateLeaderboard()
    {
        $this->db->transStart();
        $key = $this->request->getGet('key');
        $secret = getenv('CRON_SECRET_KEY');

        if ($key !== $secret) {
            return $this->response->setStatusCode(403)->setBody('Unauthorized');
        }

        $gameMapping = new GameMappingModel();
        $playersModel = new PlayersModel();
        $leaderboardModel = new LeaderboardModel();

        $yesterdayStart = date('Y-m-d 00:00:00', strtotime('-1 day'));
        $yesterdayEnd = date('Y-m-d 23:59:59', strtotime('-1 day'));
        $lbDate = date('Y-m-d', strtotime('-1 day'));
        // print($lbDate);exit();
        if ($leaderboardModel->where('lb_date', $lbDate)->countAllResults() > 0) {
            return 'Leaderboard already updated';
        }

        $mapping = $gameMapping
            ->where('gm_status', '1')
            ->where('gm_date', date('Y-m-d', strtotime('-1 day')))
            ->first();

        if (!$mapping) {
            return 'No mapping found';
        }

        $limit = (int) $mapping['gm_leaderboard_count'];

        $players = $playersModel
            ->where('player_created_at >=', $yesterdayStart)
            ->where('player_created_at <=', $yesterdayEnd)
            ->orderBy('player_rank', 'ASC')
            ->orderBy('player_score', 'DESC')
            ->limit($limit)
            ->findAll();

        if (empty($players)) {
            return 'No players in last 24 hours';
        }

        if ($leaderboardModel->where('lb_date', date('Y-m-d'))->countAllResults() > 0) {
            return 'Already executed for yesterday';
        }

        $today = date('Y-m-d');
        $todayCount = $leaderboardModel
            ->where('DATE(lb_created_at)', $today)
            ->countAllResults();

        $freeTeeCount = round(
            ($mapping['gm_leaderboard_count'] * $mapping['gm_free_tee_percentage']) / 100
        );

        $rankCounter = 0;

        foreach ($players as $p) {

            $rankCounter++;

            $lbStatus = ($rankCounter <= $freeTeeCount) ? '1' : '2';

            $todayCount++;
            $dailyNumber = str_pad($todayCount, 4, '0', STR_PAD_LEFT);

            $leaderboardModel->insert([
                'player_Id' => $p['player_Id'],
                'cust_Id' => $p['cust_Id'],
                'game_Id' => $p['game_Id'],

                'lb_coupen_code' => 'VOYC-' . date('Ymd') . '-' . $dailyNumber,
                'lb_discount' => $mapping['gm_extra_discount'],
                'lb_redeemed_status' => 1,

                'lb_rank' => $p['player_rank'],
                'lb_score' => $p['player_score'],

                'lb_status' => $lbStatus,
                'lb_date' => $lbDate,

                'lb_created_by' => 1,
                'lb_created_at' => date('Y-m-d H:i:s')
            ]);

            // daily game couter update
            $dailyCounterModel = new DailyCounterModel();

            // total players yesterday (ALL, not limited)
            $totalPlayersYesterday = $playersModel
                ->where('player_created_at >=', $yesterdayStart)
                ->where('player_created_at <=', $yesterdayEnd)
                ->countAllResults();

            $winnerCount = min($freeTeeCount, count($players));

            $winningPercentage = 0;
            if ($totalPlayersYesterday > 0) {
                $winningPercentage = round(
                    ($winnerCount / $totalPlayersYesterday) * 100,
                    2
                );
            }

            $existingCounter = $dailyCounterModel
                ->where('dgc_date', $lbDate)
                ->first();

            if ($existingCounter && isset($existingCounter['dgc_Id'])) {

                $dailyCounterModel->update($existingCounter['dgc_Id'], [
                    'dgc_player_count' => $totalPlayersYesterday,
                    'dgc_winner_count' => $winnerCount,
                    'dgc_winning_percentage' => $winningPercentage,
                    'dgc_status' => 1,
                    'dgc_updated_by' => 1,
                    'dgc_updated_at' => date('Y-m-d H:i:s'),
                ]);

            } else {

                $dailyCounterModel->insert([
                    'game_Id' => $mapping['game_Id'],
                    'dgc_player_count' => $totalPlayersYesterday,
                    'dgc_winner_count' => $winnerCount,
                    'dgc_winning_percentage' => $winningPercentage,
                    'dgc_date' => $lbDate,
                    'dgc_status' => 1,
                    'dgc_created_by' => 1,
                    'dgc_created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // Update game mapping status AFTER successful execution

            $todayDate = date('Y-m-d');
            $yesterdayDate = date('Y-m-d', strtotime('-1 day'));

            $gameMapping
                ->where('gm_date', $yesterdayDate)
                ->set(['gm_status' => '2'])
                ->update();

            $gameMapping
                ->where('gm_date', $todayDate)
                ->set(['gm_status' => '1'])
                ->update();

        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return 'Cron failed, transaction rolled back';
        }

        return 'Leaderboard updated for last 24 hours';
    }

    public function cronPerHour()
    {
        $key = $this->request->getGet('key');
        $secret = getenv('CRON_SECRET_KEY');

        if ($key !== $secret) {
            return $this->response->setStatusCode(403)->setBody('Unauthorized');
        }

        $this->db->transStart();

        $couponConfig = $this->db->table('common_table')
            ->select('value')
            ->where('field', 'coupon_lifetime_days')
            ->get()
            ->getRowArray();

        if (!$couponConfig || !is_numeric($couponConfig['value'])) {
            return 'coupon_lifetime_days not configured';
        }

        $lifetimeDays = (int) $couponConfig['value'];

        $expiryCutoff = date(
            'Y-m-d H:i:s',
            strtotime("-{$lifetimeDays} days")
        );

        $this->db->table('leaderboard')
            ->where('lb_redeemed_status', 1) // only active coupons
            ->where('lb_created_at <', $expiryCutoff)
            ->update([
                'lb_redeemed_status' => 3
            ]);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return 'Coupon expiry cron failed';
        }

        return 'Expired leaderboard coupons updated successfully';
    }


}
