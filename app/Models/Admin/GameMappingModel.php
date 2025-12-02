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
        // MAIN QUERY (Return Rows)
        $builder = $this->db->table($this->table)
            ->select("games_mapping.*, game.game_Name as game_name")
            ->join("game", "game.game_Id = games_mapping.game_Id", "left")
            ->where("games_mapping.gm_status !=", '9');

        // Search filter
        if (!empty($search)) {
            $builder->groupStart()
                ->like("game.game_Name", $search)
                ->orLike("games_mapping.gm_date", $search)
                ->groupEnd();
        }

        // Total count (ONLY active)
        $totalBuilder = $this->db->table($this->table)
            ->where("gm_status !=", '9');
        $total = $totalBuilder->countAllResults();

        // Filtered count (ONLY active)
        $filteredBuilder = $this->db->table($this->table)
            ->join("game", "game.game_Id = games_mapping.game_Id", "left")
            ->where("games_mapping.gm_status !=", '9');

        if (!empty($search)) {
            $filteredBuilder->groupStart()
                ->like("game.game_Name", $search)
                ->orLike("games_mapping.gm_date", $search)
                ->groupEnd();
        }

        $filtered = $filteredBuilder->countAllResults();

        // Order + pagination
        $builder->orderBy($orderBy, $orderDir);
        $builder->limit($length, $start);

        $query = $builder->get();
        $result = $query->getResultArray();

        return [
            'data' => $result,
            'total' => $total,
            'filtered' => $filtered
        ];
    }


}
