<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class GameMappingModel extends Model
{
    protected $table = 'games_mapping';
    protected $primaryKey = 'gm_Id';

    protected $allowedFields = [
        'game_Id',
        'gm_date',
        'gm_tokens',
        'gm_status',
        'gm_leaderboard_count',
        'gm_extra_discount',
        'gm_free_tee_percentage',
        'gm_created_by',
        'gm_created_at',
        'gm_updated_by',
        'gm_updated_at'
    ];

    protected $useTimestamps = false;

    public function getDatatables($search = null, $start = 0, $length = 10, $orderBy = 'gm_Id', $orderDir = 'DESC')
    {
        $search = preg_replace('/\s+/', '', $search);
        $builder = $this->db->table($this->table)
            ->select("games_mapping.*, game.game_Name as game_name")
            ->join("game", "game.game_Id = games_mapping.game_Id", "left")
            ->where("games_mapping.gm_status !=", '9');
        if (!empty($search)) {
            $escaped = $this->db->escapeLikeString($search);

            $builder->groupStart()
                ->where("REPLACE(game.game_Name, ' ', '') LIKE '%{$escaped}%'", null, false)
                ->orWhere("REPLACE(games_mapping.gm_date, ' ', '') LIKE '%{$escaped}%'", null, false)
                ->groupEnd();
        }
        $total = $this->db->table($this->table)
            ->where("gm_status !=", '9')
            ->countAllResults();
        $filteredBuilder = $this->db->table($this->table)
            ->join("game", "game.game_Id = games_mapping.game_Id", "left")
            ->where("games_mapping.gm_status !=", '9');

        if (!empty($search)) {
            $escaped = $this->db->escapeLikeString($search);

            $filteredBuilder->groupStart()
                ->where("REPLACE(game.game_Name, ' ', '') LIKE '%{$escaped}%'", null, false)
                ->orWhere("REPLACE(games_mapping.gm_date, ' ', '') LIKE '%{$escaped}%'", null, false)
                ->groupEnd();
        }

        $filtered = $filteredBuilder->countAllResults();
        $builder->orderBy($orderBy, $orderDir);
        $builder->limit($length, $start);

        $result = $builder->get()->getResultArray();

        return [
            'data' => $result,
            'total' => $total,
            'filtered' => $filtered
        ];
    }

    public function getTodayLeaderboardCount($today)
    {
        $row = $this->where('gm_date', $today)->first();
        return $row['gm_leaderboard_count'] ?? 10; // default
    }
    public function getTodayActiveGame()
    {
        $today = date('Y-m-d');

        return $this->where('gm_date', $today)
                    ->where('gm_status', 1)
                    ->first();
    }
}
