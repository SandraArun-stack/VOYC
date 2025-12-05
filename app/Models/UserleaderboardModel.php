<?php
namespace App\Models;

use CodeIgniter\Model;


class UserleaderboardModel extends Model
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

    public function getUserLeaderboardData($userId, $postData)
    {
        $searchValue = trim(preg_replace('/\s+/', '', $postData['search']['value'] ?? ''));

        $builder = $this->db->table('players p');

        $builder->select("
            p.player_Id,
            p.player_created_at,
            p.player_score,
            p.player_rank,
            p.player_winning_status,
            g.game_name
        ");

        $builder->join('game g', 'g.game_Id = p.game_Id', 'left');
        $builder->where('p.cust_Id', $userId);
        $builder->orderBy('p.player_created_at', 'DESC');

        if (!empty($searchValue)) {
            $escaped = $this->db->escapeLikeString($searchValue);

            $builder->groupStart();
            $builder->where("REPLACE(REPLACE(g.game_name,' ',''), '\t','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(REPLACE(p.player_score,' ',''), '\t','') LIKE '%{$escaped}%'", null, false);
            $builder->groupEnd();
        }

        $length = $postData['length'] ?? 10;
        $start  = $postData['start'] ?? 0;

        if ($length != -1) {
            $builder->limit($length, $start);
        }

        $result = $builder->get()->getResultArray();

        foreach ($result as &$row) {
            $row['player_created_at'] = !empty($row['player_created_at'])
                ? date("d-m-Y", strtotime($row['player_created_at']))
                : "N/A";
        }

        return $result;
    }
    public function countAllUserRows($userId)
    {
        return $this->db->table('players')
            ->where('cust_Id', $userId)
            ->countAllResults();
    }

    public function countFilteredRows($userId, $postData)
    {
        $searchValue = trim(preg_replace('/\s+/', '', $postData['search']['value'] ?? ''));

        $builder = $this->db->table('players p')
            ->join('game g', 'g.game_Id = p.game_Id', 'left');

        $builder->where('p.cust_Id', $userId);

        if (!empty($searchValue)) {
            $escaped = $this->db->escapeLikeString($searchValue);

            $builder->groupStart();
            $builder->where("REPLACE(REPLACE(g.game_name,' ',''), '\t','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(REPLACE(p.player_score,' ',''), '\t','') LIKE '%{$escaped}%'", null, false);
            $builder->groupEnd();
        }

        return $builder->countAllResults();
    }



}