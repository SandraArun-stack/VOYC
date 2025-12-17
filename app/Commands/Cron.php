<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\Admin\GameMappingModel;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\LeaderboardModel;

class Cron extends BaseCommand
{
    protected $group       = 'cron';
    protected $name        = 'cron:updateLeaderboard';
    protected $description = 'Update leaderboard for the last 24 hours';

    public function run(array $params)
    {
        $gameMapping = new GameMappingModel();
        $playersModel = new PlayersModel();
        $leaderboardModel = new LeaderboardModel();

        $now = date('Y-m-d H:i:s');
        $last24Hours = date('Y-m-d H:i:s', strtotime('-24 hours'));
        $lbDate = date('Y-m-d');

        // Prevent duplicate run
        if ($leaderboardModel->where('lb_date', $lbDate)->countAllResults() > 0) {
            CLI::write('Leaderboard already updated today', 'yellow');
            return;
        }

        $mapping = $gameMapping->orderBy('gm_date', 'DESC')->first();
        if (!$mapping) {
            CLI::error('No mapping found');
            return;
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
            CLI::write('No players found in last 24 hours', 'yellow');
            return;
        }

        foreach ($players as $p) {
            $leaderboardModel->insert([
                'player_Id'      => $p['player_Id'],
                'lb_rank'        => $p['player_rank'],
                'lb_score'       => $p['player_score'],
                'cust_Id'        => $p['cust_Id'],
                'lb_date'        => $p['player_created_at'],
                'lb_status'      => 1,
                'lb_discount'    => $mapping['gm_extra_discount'],
                'lb_created_by'  => 0,
                'lb_created_at'  => date('Y-m-d H:i:s')
            ]);
        }

        CLI::write('Leaderboard updated successfully for the last 24 hours', 'green');
    }
}

