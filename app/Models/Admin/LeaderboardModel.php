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
        'player_Id',
        'lb_created_by',
        'lb_created_at',
        'lb_updated_by',
        'lb_updated_at'
    ];

    public function getDatatables()
    {
        $postData = service('request')->getPost();
        $searchValue = '';

        if (!empty($postData['search']['value'])) {
            $searchValue = $this->db->escapeLikeString(trim($postData['search']['value']));
        }

        $builder = $this->db->table('leaderboard lb');
        $builder->select('
            lb.lb_Id,
            lb.lb_date,
            lb.lb_score,
            lb.lb_rank,
            g.game_name,
            c.cust_Name AS player
        ');

        $builder->join('game g', 'g.game_Id = lb.game_Id', 'left');
        $builder->join('customer c', 'c.cust_Id = lb.cust_Id', 'left');

        if (!empty($searchValue)) {
            $builder->groupStart();
            $builder->like('g.game_name', $searchValue);
            $builder->orLike('c.cust_Name', $searchValue);
            $builder->groupEnd();
        }

        $builder->orderBy('lb.lb_Id', 'DESC');

        if (!empty($postData['length']) && $postData['length'] != -1) {
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
        $searchValue = '';

        if (!empty($postData['search']['value'])) {
            $searchValue = $this->db->escapeLikeString(trim($postData['search']['value']));
        }

        $builder = $this->db->table('leaderboard lb');
        $builder->select('COUNT(*) as total');

        $builder->join('game g', 'g.game_Id = lb.game_Id', 'left');
        $builder->join('customer c', 'c.cust_Id = lb.cust_Id', 'left');

        if (!empty($searchValue)) {
            $builder->groupStart();
            $builder->like('g.game_name', $searchValue);
            $builder->orLike('c.cust_Name', $searchValue);
            $builder->groupEnd();
        }

        return $builder->get()->getRow()->total;
    }
}
