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
        $builder = $this->db->table($this->table)
            ->select("players.*, customer.cust_name as customer_name, game.game_name as game_name")
            ->join("customer", "customer.cust_Id = players.cust_Id", "left")
            ->join("game", "game.game_Id = players.game_Id", "left")
            ->where("players.player_status <>", '9');

        if (!empty($search)) {
            $builder->groupStart()
                ->like("customer.cust_name", $search)
                ->orLike("game.game_name", $search)
                ->orLike("players.player_date", $search)
                ->groupEnd();
        }

        $total = $this->db->table($this->table)
            ->where("player_status <>", '9')
            ->countAllResults();

        $filteredBuilder = $this->db->table($this->table)
            ->join("customer", "customer.cust_Id = players.cust_Id", "left")
            ->join("game", "game.game_Id = players.game_Id", "left")
            ->where("players.player_status <>", '9');

        if (!empty($search)) {
            $filteredBuilder->groupStart()
                ->like("customer.cust_name", $search)
                ->orLike("game.game_name", $search)
                ->orLike("players.player_date", $search)
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

}
