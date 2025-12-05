<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class DailyCounterModel extends Model
{
    protected $table = 'daily_game_counter';
    protected $primaryKey = 'dgc_Id';

    protected $allowedFields = [
        'game_Id',
        'dgc_player_count',
        'dgc_winner_count',
        'dgc_winning_percentage',
        'dgc_date',
        'dgc_status',
        'dgc_created_by',
        'dgc_created_at',
        'dgc_updated_by',
        'dgc_updated_at'
    ];
    public function getDatatables()
    {
        $postData = service('request')->getPost();
        $searchValue = trim($postData['search']['value'] ?? '');
        $searchValue = preg_replace('/\s+/', '', $searchValue);

        $builder = $this->db->table('daily_game_counter dgc');
        $builder->select("
            dgc.dgc_Id,
            dgc.dgc_date,
            dgc.dgc_player_count,
            dgc.dgc_winner_count,
            dgc.dgc_winning_percentage,
            g.game_name
        ", false);

        $builder->join('game g', 'g.game_Id = dgc.game_Id', 'left');

        if (!empty($searchValue)) {
            $escaped = $this->db->escapeLikeString($searchValue);

            $builder->groupStart();
            $builder->where("REPLACE(g.game_name,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(dgc.dgc_player_count,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(dgc.dgc_winner_count,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(dgc.dgc_winning_percentage,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->groupEnd();
        }

        $builder->orderBy('dgc.dgc_date', 'DESC');

        if ($postData['length'] != -1) {
            $builder->limit($postData['length'], $postData['start']);
        }

        return $builder->get()->getResultArray();
    }
    public function countAll()
    {
        return $this->db->table('daily_game_counter')->countAllResults();
    }
    public function countFiltered()
    {
        $postData = service('request')->getPost();
        $searchValue = trim($postData['search']['value'] ?? '');
        $searchValue = preg_replace('/\s+/', '', $searchValue);

        $builder = $this->db->table('daily_game_counter dgc');
        $builder->select('COUNT(*) as total');
        $builder->join('game g', 'g.game_Id = dgc.game_Id', 'left');

        if (!empty($searchValue)) {
            $escaped = $this->db->escapeLikeString($searchValue);

            $builder->groupStart();
            $builder->where("REPLACE(g.game_name,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(dgc.dgc_player_count,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(dgc.dgc_winner_count,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(dgc.dgc_winning_percentage,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->groupEnd();
        }

        return $builder->get()->getRow()->total;
    }
}
