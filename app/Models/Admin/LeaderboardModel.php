<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class LeaderboardModel extends Model
{
    protected $table = 'leaderboard';
    protected $primaryKey = 'lb_Id';
    protected $allowedFields = [
        'cust_Id',
        'game_Id',
        'lb_date',
        'lb_score',
        'lb_rank',
        'lb_discount',
        'lb_status',
        'player_Id',
        'lb_created_by',
        'lb_created_at',
        'lb_updated_by',
        'lb_updated_at'
    ];
    public function getDatatables()
    {
        $postData = service('request')->getPost();
        $searchValue = trim($postData['search']['value'] ?? '');
        $searchValue = preg_replace('/\s+/', '', $searchValue);

        $builder = $this->db->table('leaderboard lb');
        $builder->select("
            lb.lb_Id,
            lb.lb_created_at,
            lb.lb_score,
            lb.lb_status,
            g.game_name,
            c.cust_Name AS player,
            (
                SELECT COUNT(*) + 1
                FROM leaderboard l2
                WHERE l2.lb_score > lb.lb_score
                AND DATE(l2.lb_created_at) = DATE(lb.lb_created_at)
            ) AS lb_rank
        ", false);

        $builder->join('game g', 'g.game_Id = lb.game_Id', 'left');
        $builder->join('customer c', 'c.cust_Id = lb.cust_Id', 'left');

        if (!empty($searchValue)) {
            $escaped = $this->db->escapeLikeString($searchValue);

            $builder->groupStart();
            $builder->where("REPLACE(REPLACE(g.game_name,' ','') , '\t','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(REPLACE(c.cust_Name,' ','') , '\t','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(lb.lb_score,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(CAST(lb.lb_rank AS CHAR),' ','') LIKE '%{$escaped}%'", null, false);
            $builder->groupEnd();
        }

        $builder->orderBy('lb.lb_created_at', 'DESC');

        if ($postData['length'] != -1) {
            $builder->limit($postData['length'], $postData['start']);
        }

        return $builder->get()->getResultArray();
    }
    public function countAll()
    {
        return $this->db->table('leaderboard')->countAllResults();
    }
    public function countFiltered()
    {
        $postData = service('request')->getPost();
        $searchValue = trim($postData['search']['value'] ?? '');
        $searchValue = preg_replace('/\s+/', '', $searchValue);

        $builder = $this->db->table('leaderboard lb');
        $builder->select('COUNT(*) as total');

        $builder->join('game g', 'g.game_Id = lb.game_Id', 'left');
        $builder->join('customer c', 'c.cust_Id = lb.cust_Id', 'left');

        if (!empty($searchValue)) {
            $escaped = $this->db->escapeLikeString($searchValue);

            $builder->groupStart();
            $builder->where("REPLACE(REPLACE(g.game_name,' ','') , '\t','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(REPLACE(c.cust_Name,' ','') , '\t','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(lb.lb_score,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(CAST(lb.lb_rank AS CHAR),' ','') LIKE '%{$escaped}%'", null, false);
            $builder->groupEnd();
        }

        return $builder->get()->getRow()->total;
    }

    public function insertPlayersToLeaderboard($players, $gameId = 1)
    {
        foreach ($players as $p) {
            $this->insert([
                'player_Id' => $p['player_Id'],
                'cust_Id' => $p['cust_Id'],
                'game_Id' => $gameId,
                'lb_date' => date('Y-m-d'),
                'lb_rank' => $p['player_rank'],
                'lb_score' => $p['player_score'],
                'lb_status' => 0,
                'lb_created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

}
