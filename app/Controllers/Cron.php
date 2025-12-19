<?php

namespace App\Controllers;
use CodeIgniter\Controller;

use App\Models\Admin\GameMappingModel;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\LeaderboardModel;
use App\Models\Admin\DailyCounterModel;
use App\Models\Admin\UserSubscriptionsModel;
use App\Models\Admin\WalletModel;
use App\Models\Admin\SubscriptionModel;

class Cron extends Controller
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
    }
    //update leaderboard, daily game counter, game mapping table status, Reacharge the Wallet runs every day at 1:00 AM
    public function updateLeaderboard()
    {
        $key = $this->request->getGet('key');
        $secret = getenv('CRON_SECRET_KEY');

        if ($key !== $secret) {
            return $this->response->setStatusCode(403)->setBody('Unauthorized');
        }

        $gameMapping = new GameMappingModel();
        $playersModel = new PlayersModel();
        $leaderboardModel = new LeaderboardModel();
        $dailyCounterModel = new DailyCounterModel();
        $walletModel = new WalletModel();
        $subscriptionModel = new SubscriptionModel();
        
        $yesterdayStart = date('Y-m-d 00:00:00', strtotime('-1 day'));
        $yesterdayEnd = date('Y-m-d 23:59:59', strtotime('-1 day'));
        $lbDate = date('Y-m-d', strtotime('-1 day'));

        // ---------- VALIDATIONS (NO TRANSACTION YET) ----------
        if ($leaderboardModel->where('lb_date', $lbDate)->countAllResults() > 0) {
            return 'Leaderboard already updated';
        }

        $mapping = $gameMapping
            ->where('gm_status', '1')
            ->where('gm_date', $lbDate)
            ->first();

        if (!$mapping) {
            return 'No mapping found';
        }

        $players = $playersModel
            ->where('player_created_at >=', $yesterdayStart)
            ->where('player_created_at <=', $yesterdayEnd)
            ->orderBy('player_rank', 'ASC')
            ->orderBy('player_score', 'DESC')
            ->limit((int) $mapping['gm_leaderboard_count'])
            ->findAll();

        if (empty($players)) {
            return 'No players in last 24 hours';
        }

        // ---------- START TRANSACTION ----------
        $this->db->transBegin();

        try {

            $todayCount = $leaderboardModel
                ->where('lb_created_at >=', date('Y-m-d') . ' 00:00:00')
                ->where('lb_created_at <=', date('Y-m-d') . ' 23:59:59')
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
                    'lb_created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // ---------- DAILY COUNTER (ONCE) ----------
            $totalPlayersYesterday = $playersModel
                ->where('player_created_at >=', $yesterdayStart)
                ->where('player_created_at <=', $yesterdayEnd)
                ->countAllResults();

            $winnerCount = min($freeTeeCount, count($players));
            $winningPercentage = $totalPlayersYesterday > 0
                ? round(($winnerCount / $totalPlayersYesterday) * 100, 2)
                : 0;

            $existingCounter = $dailyCounterModel
                ->where('dgc_date', $lbDate)
                ->first();

            if ($existingCounter) {
                $dailyCounterModel->update($existingCounter['dgc_Id'], [
                    'dgc_player_count' => $totalPlayersYesterday,
                    'dgc_winner_count' => $winnerCount,
                    'dgc_winning_percentage' => $winningPercentage,
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

            $wallet = $walletModel
                ->where('lb_created_at >=', date('Y-m-d') . ' 00:00:00')
                ->where('lb_created_at <=', date('Y-m-d') . ' 23:59:59')
                ->countAllResults();


            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            $this->db->transCommit();
            return 'Leaderboard updated for last 24 hours';

        } catch (\Throwable $e) {
            $this->db->transRollback();
            log_message('error', 'Leaderboard cron failed: ' . $e->getMessage());
            return 'Cron failed, transaction rolled back';
        }
    }

    public function updateGameMapping()
    {
        $key = $this->request->getGet('key');
        $secret = getenv('CRON_SECRET_KEY');

        if ($key !== $secret) {
            return $this->response->setStatusCode(403)->setBody('Unauthorized');
        }

        $this->db->transBegin();

        try {
            $todayDate = date('Y-m-d');
            $yesterdayDate = date('Y-m-d', strtotime('-1 day'));

            $gmYesterday = new GameMappingModel();
            $gmToday = new GameMappingModel();

            $gmYesterday
                ->where('gm_date', $yesterdayDate)
                ->set(['gm_status' => '2'])
                ->update();

            $gmToday
                ->where('gm_date', $todayDate)
                ->set(['gm_status' => '1'])
                ->update();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Game mapping update failed');
            }

            $this->db->transCommit();
            return 'Game mapping updated successfully';

        } catch (\Throwable $e) {

            $this->db->transRollback();
            log_message('error', 'updateGameMapping failed: ' . $e->getMessage());
            return 'Game mapping update failed';
        }
    }


    // update leaderboard coupons lb_redeem_status, user subscriptions status, user wallet status every hour
    public function cronPerHour()
    {
        $key = $this->request->getGet('key');
        $secret = getenv('CRON_SECRET_KEY');

        if ($key !== $secret) {
            return $this->response->setStatusCode(403)->setBody('Unauthorized');
        }

        $this->db->transStart();

        /* =====================================================
         *  LEADERBOARD COUPON EXPIRY
         * ===================================================== */

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
            ->where('lb_redeemed_status', '1') // only active coupons
            ->where('lb_created_at <', $expiryCutoff)
            ->update([
                'lb_redeemed_status' => '3'
            ]);

        /* =====================================================
         * USER SUBSCRIPTION EXPIRY and WALLET STATUS UPDATE
         * ===================================================== */

        $userSubscriptionsModel = new UserSubscriptionsModel();
        $walletModel = new WalletModel();

        $now = date('Y-m-d H:i:s');

        // Get expired subscriptions FIRST
        $expiredSubscriptions = $userSubscriptionsModel
            ->select('usersub_Id')
            ->where('usersub_status', '1')
            ->where('usersub_expiry <', $now)
            ->findAll();

        if (!empty($expiredSubscriptions)) {

            $expiredSubIds = array_column($expiredSubscriptions, 'usersub_Id');

            // Update subscriptions
            $userSubscriptionsModel
                ->whereIn('usersub_Id', $expiredSubIds)
                ->set([
                    'usersub_status' => '2',
                    'usersub_updated_at' => $now,
                    'usersub_updated_by' => 1
                ])
                ->update();

            // Update related wallets
            $walletModel
                ->whereIn('usersub_Id', $expiredSubIds)
                ->where('uw_status', '1')
                ->set([
                    'uw_status' => '2',
                    'uw_updated_at' => $now,
                    'uw_updated_by' => 1
                ])
                ->update();
        }


        /* ===================================================== */

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return 'Coupon expiry cron failed';
        }

        return 'Leaderboard coupons & user subscriptions expired successfully';
    }


}
