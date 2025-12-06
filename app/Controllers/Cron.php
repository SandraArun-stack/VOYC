<?php

namespace App\Controllers;

use App\Models\Admin\GameMappingModel;
use App\Models\Admin\PlayerModel;
use App\Models\Admin\LeaderboardModel;
use CodeIgniter\Controller;

class Cron extends Controller
{
    public function updateLeaderboard()
    {
        $gameMapping = new GameMappingModel();
        $playerModel = new PlayerModel();
        $leaderboardModel = new LeaderboardModel();

        $today = date('Y-m-d');

        $mapping = $gameMapping->where('gm_date', $today)->first();

        if (!$mapping) {
            return "No game mapping found for today!";
        }

        $limit = $mapping['gm_leaderboard_count']; // Example: 30 players
        
        $players = $playerModel
            ->orderBy('player_rank', 'ASC')
            ->orderBy('player_score', 'DESC')
            ->limit($limit)
            ->findAll();

        if (empty($players)) {
            return "No players found!";
        }

        foreach ($players as $p) {
            $leaderboardModel->insert([
                'player_id'  => $p['player_id'],
                'player_rank' => $p['player_rank'],
                'player_score' => $p['player_score'],
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return "Leaderboard updated successfully!";
    }
}
