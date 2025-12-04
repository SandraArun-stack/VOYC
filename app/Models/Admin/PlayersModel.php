<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class PlayersModel extends Model
{
    protected $table = 'players';
    protected $primaryKey = 'player_Id';

    protected $allowedFields = [
        'game_Id',
        'cust_Id',
        'player_date',
        'player_score',
        'player_rank',
        'player_winning_status',
        'player_status',
        'player_created_at',
        'player_created_by',
        'player_updated_at',
        'player_updated_by'
    ];

    public function getDatatables($search = null, $start = 0, $length = 10, $orderBy = 'player_Id', $orderDir = 'DESC')
    {

        $search = trim($search);
        $searchNoSpace = preg_replace('/\s+/', '', $search);
        $builder = $this->db->table($this->table)
            ->select("
                players.*,
                DATE_FORMAT(players.player_date, '%d-%m-%Y') AS player_date,
                CONCAT(
                    UPPER(LEFT(customer.cust_name, 1)),
                    LOWER(SUBSTRING(customer.cust_name, 2))
                ) AS customer_name,
                CONCAT(
                    UPPER(LEFT(game.game_name, 1)),
                    LOWER(SUBSTRING(game.game_name, 2))
                ) AS game_name
            ", false)
            ->join("customer", "customer.cust_Id = players.cust_Id", "left")
            ->join("game", "game.game_Id = players.game_Id", "left")
            ->where("players.player_status <>", 9);
        if (!empty($search)) {
            $escaped = $this->db->escapeLikeString($searchNoSpace);

            $builder->groupStart()
                ->where("REPLACE(customer.cust_name, ' ', '') LIKE '%{$escaped}%'", null, false)
                ->orWhere("REPLACE(game.game_name, ' ', '') LIKE '%{$escaped}%'", null, false)
                ->orWhere("REPLACE(players.player_date, ' ', '') LIKE '%{$escaped}%'", null, false)
                ->groupEnd();
        }
        $total = $this->db->table($this->table)
            ->where("player_status <>", 9)
            ->countAllResults();
        $filteredBuilder = $this->db->table($this->table)
            ->join("customer", "customer.cust_Id = players.cust_Id", "left")
            ->join("game", "game.game_Id = players.game_Id", "left")
            ->where("players.player_status <>", 9);

        if (!empty($search)) {
            $escaped = $this->db->escapeLikeString($searchNoSpace);

            $filteredBuilder->groupStart()
                ->where("REPLACE(customer.cust_name, ' ', '') LIKE '%{$escaped}%'", null, false)
                ->orWhere("REPLACE(game.game_name, ' ', '') LIKE '%{$escaped}%'", null, false)
                ->orWhere("REPLACE(players.player_date, ' ', '') LIKE '%{$escaped}%'", null, false)
                ->groupEnd();
        }

        $filtered = $filteredBuilder->countAllResults();
        $builder->orderBy($orderBy, $orderDir)
            ->limit($length, $start);

        $result = $builder->get()->getResultArray();

        return [
            'data' => $result,
            'total' => $total,
            'filtered' => $filtered
        ];
    }

    // public function getTodayPlayers($today, $limit)
    // {
    //     return $this->select('players.*, customer.cust_Name AS player_name')
    //         ->join('customer', 'customer.cust_Id = players.cust_Id', 'left')
    //         ->where('DATE(players.player_created_at)', $today)
    //         ->orderBy('player_score', 'desc')
    //         ->limit($limit)
    //         ->findAll();
    // }

    public function getTodayPlayers($today, $limit, $sessionUserId = null)
    {
        $players = $this->select('players.*, customer.cust_Name AS player_name')
            ->join('customer', 'customer.cust_Id = players.cust_Id', 'left')
            ->where('DATE(players.player_created_at)', $today)
            ->orderBy('player_score', 'desc')
            ->limit($limit)
            ->findAll();

        $lastPlayer = null;

        if ($sessionUserId) {
            $exists = false;
            foreach ($players as $p) {
                if ($p['cust_Id'] == $sessionUserId) {
                    $exists = true;
                    break;
                }
            }

            if (!$exists) {
                $lastPlayer = $this->select('players.*, customer.cust_Name AS player_name')
                    ->join('customer', 'customer.cust_Id = players.cust_Id', 'left')
                    ->where('DATE(players.player_created_at)', $today)
                    ->where('players.cust_Id', $sessionUserId)
                    ->first();
            }
        }

        return [
            'players' => $players,
            'lastPlayer' => $lastPlayer
        ];
    }

}
