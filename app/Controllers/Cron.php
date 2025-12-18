<?php

namespace App\Controllers;

use App\Models\Admin\GameMappingModel;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\LeaderboardModel;
use CodeIgniter\Controller;

class Cron extends Controller
{
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

        $now = date('Y-m-d H:i:s');
        $last24Hours = date('Y-m-d H:i:s', strtotime('-24 hours'));
        $lbDate = date('Y-m-d');

        if ($leaderboardModel->where('lb_date', $lbDate)->countAllResults() > 0) {
            return 'Leaderboard already updated';
        }

        $mapping = $gameMapping->orderBy('gm_date', 'DESC')->first();
        if (!$mapping) {
            return 'No mapping found';
        }

        $limit = (int) $mapping['gm_leaderboard_count'];

        $players = $playersModel
            ->where('player_created_at >=', $last24Hours)
            ->where('player_created_at <=', $now)
            ->orderBy('player_rank', 'ASC')
            ->orderBy('player_score', 'DESC')
            ->limit($limit)
            ->findAll();

        if (empty($players)) {
            return 'No players in last 24 hours';
        }
        if ($leaderboardModel->where('lb_date', date('Y-m-d'))->countAllResults() > 0) {
            return 'Already executed today';
        }

        $today = date('Y-m-d');
        $todayCount = $leaderboardModel
            ->where('DATE(lb_created_at)', $today)
            ->countAllResults();

        $freeTeeCount = round(
            ($mapping['gm_leaderboard_count'] * $mapping['gm_free_tee_percentage']) / 100
        );
// print_r($freeTeeCount); exit;
        $rankCounter = 0;

        foreach ($players as $p) {

            $rankCounter++;

            // Decide status
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

                'lb_status' => $lbStatus, // ✅ IMPORTANT
                'lb_date' => date('Y-m-d'),

                'lb_created_by' => 1,
                'lb_created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return 'Leaderboard updated for last 24 hours';
    }


}
