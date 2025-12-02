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
        $searchValue = $postData['search']['value'] ?? '';

        // Yesterday
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        $builder = $this->db->table('leaderboard lb');

        // Dynamic rank calculation
        $builder->select("
            lb.lb_Id,
            lb.lb_date,
            lb.lb_score,
            lb.lb_status,
            g.game_name,
            c.cust_Name AS player,
            (
                SELECT COUNT(*) + 1
                FROM leaderboard l2
                WHERE l2.lb_score > lb.lb_score
                AND DATE(l2.lb_date) = DATE(lb.lb_date)
            ) AS lb_rank
        ", false);

        $builder->join('game g', 'g.game_Id = lb.game_Id', 'left');
        $builder->join('customer c', 'c.cust_Id = lb.cust_Id', 'left');

        // Show only yesterday winners
        $builder->where('DATE(lb.lb_date)', $yesterday);

        // Search
        if (!empty($searchValue)) {
            $builder->groupStart();
            $builder->like('g.game_name', $searchValue);
            $builder->orLike('c.cust_Name', $searchValue);
            $builder->orLike('lb.lb_score', $searchValue);
            $builder->orLike('lb.lb_date', $searchValue);
            $builder->groupEnd();
        }

        // Sort highest score → rank 1 first
        $builder->orderBy('lb.lb_score', 'DESC');

        
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
        $searchValue = $postData['search']['value'] ?? '';

        $yesterday = date('Y-m-d', strtotime('-1 day'));

        $builder = $this->db->table('leaderboard lb');
        $builder->select('COUNT(*) as total');

        $builder->join('game g', 'g.game_Id = lb.game_Id', 'left');
        $builder->join('customer c', 'c.cust_Id = lb.cust_Id', 'left');

        $builder->where('DATE(lb.lb_date)', $yesterday);

        if (!empty($searchValue)) {
            $builder->groupStart();
            $builder->like('g.game_name', $searchValue);
            $builder->orLike('c.cust_Name', $searchValue);
            $builder->orLike('lb.lb_score', $searchValue);
            $builder->orLike('lb.lb_date', $searchValue);
            $builder->groupEnd();
        }

        return $builder->get()->getRow()->total;
    }

}
