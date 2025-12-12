<?php
namespace App\Models;

use CodeIgniter\Model;


class UserleaderboardModel extends Model
{

    protected $table = 'leaderboard';
    protected $primaryKey = 'lb_Id';

    protected $allowedFields = [
        'player_Id',
        'cust_Id',
        'game_Id',
        'lb_date',
        'lb_score',
        'lb_rank',
        'lb_discount',
        'lb_created_at',
        'lb_updated_by',
        'lb_updated_at',
        'lb_coupen_code',
        'lb_status',
        'lb_redeemed_status',
        'lb_created_by'
    ];

    public function getleaderboard()
    {
        // Read POST data directly
        $postData = service('request')->getPost();

        // Clean search input
        $searchValue = trim(preg_replace('/\s+/', '', $postData['search']['value'] ?? ''));

        $builder = $this->db->table('leaderboard lb');

        $builder->select("
        lb.*,
        c.cust_name,
        g.game_name
    ");

        $builder->join('game g', 'g.game_Id = lb.game_Id', 'left');
        $builder->join('customer c', 'c.cust_Id = lb.cust_Id', 'left');

        // SEARCH
        if (!empty($searchValue)) {
            $escaped = $this->db->escapeLikeString($searchValue);
            $builder->groupStart()
                ->where("REPLACE(g.game_name, ' ', '') LIKE '%{$escaped}%'", null, false)
                ->orWhere("REPLACE(c.cust_name, ' ', '') LIKE '%{$escaped}%'", null, false)
                ->groupEnd();
        }

        // PAGINATION
        $length = $postData['length'] ?? 10;
        $start = $postData['start'] ?? 0;

        if ($length != -1) {
            $builder->limit($length, $start);
        }
        $builder->orderBy('lb.lb_date', 'DESC');
        $builder->orderBy('lb.lb_rank', 'ASC');

        return $builder->get()->getResultArray();
    }


    public function countAllUserRows()
    {
        return $this->db->table('leaderboard')
            ->countAllResults();
    }

    public function countFilteredRows()
    {
        $postData = service('request')->getPost();

        $searchValue = trim(preg_replace('/\s+/', '', $postData['search']['value'] ?? ''));

        $builder = $this->db->table('leaderboard lb');
        $builder->join('game g', 'g.game_Id = lb.game_Id', 'left');
        $builder->join('customer c', 'c.cust_Id = lb.cust_Id', 'left');

        if (!empty($searchValue)) {
            $escaped = $this->db->escapeLikeString($searchValue);
            $builder->groupStart()
                ->where("REPLACE(g.game_name, ' ', '') LIKE '%{$escaped}%'", null, false)
                ->orWhere("REPLACE(c.cust_name, ' ', '') LIKE '%{$escaped}%'", null, false)
                ->groupEnd();
        }

        return $builder->countAllResults();
    }

    public function getUserRedemption($userId)
    {
        return $this->where('cust_Id', $userId)
            ->orderBy('lb_Id', 'DESC')
            ->findAll();
    }




}