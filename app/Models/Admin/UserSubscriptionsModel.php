<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class UserSubscriptionsModel extends Model
{
    protected $table = 'user_subscription';
    protected $primaryKey = 'usersub_Id';
    protected $allowedFields = [
        'transaction_Id',
        'cust_Id',
        'sp_Id',
        'usersub_expiry',
        'usersub_status',
        'usersub_discount',
        'usersub_created_by',
        'usersub_created_at',
        'usersub_updated_by',
        'usersub_updated_at'
    ];

    protected $returnType = 'array';

    public function getDatatables()
    {
        $postData = service('request')->getPost();
        $searchValue = trim($postData['search']['value'] ?? '');
        $searchValue = preg_replace('/\s+/', '', $searchValue);

        $builder = $this->db->table('user_subscription us');
        $builder->select('
            us.usersub_Id,
            us.usersub_status,
            us.usersub_discount,
            us.usersub_created_at,
            us.usersub_expiry,
            c.cust_Name AS user_name,
            s.sp_plan_name AS plan_name
        ');

        $builder->join('customer c', 'c.cust_Id = us.cust_Id', 'left');
        $builder->join('subscription_plan s', 's.sp_Id = us.sp_Id', 'left');

        $builder->whereIn('us.usersub_status', [1, 2]);
        if (!empty($searchValue)) {
            $escaped = $this->db->escapeLikeString($searchValue);

            $builder->groupStart();
            $builder->where("REPLACE(c.cust_Name, ' ', '') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(s.sp_plan_name, ' ', '') LIKE '%{$escaped}%'", null, false);
            $builder->groupEnd();
        }

        if (!empty($postData['length']) && $postData['length'] != -1) {
            $builder->limit($postData['length'], $postData['start']);
        }

        $builder->orderBy('us.usersub_Id', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function countAll()
    {
        return $this->db->table('user_subscription')
            ->whereIn('usersub_status', [1, 2])
            ->countAllResults();
    }
    public function countFiltered()
    {
        $postData = service('request')->getPost();
        $searchValue = trim($postData['search']['value'] ?? '');
        $searchValue = preg_replace('/\s+/', '', $searchValue);

        $builder = $this->db->table('user_subscription us');
        $builder->select('COUNT(us.usersub_Id) AS total');

        $builder->join('customer c', 'c.cust_Id = us.cust_Id', 'left');
        $builder->join('subscription_plan s', 's.sp_Id = us.sp_Id', 'left');

        $builder->whereIn('us.usersub_status', [1, 2]);
        if (!empty($searchValue)) {
            $escaped = $this->db->escapeLikeString($searchValue);

            $builder->groupStart();
            $builder->where("REPLACE(c.cust_Name, ' ', '') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(s.sp_plan_name, ' ', '') LIKE '%{$escaped}%'", null, false);
            $builder->groupEnd();
        }

        $row = $builder->get()->getRowArray();
        return $row['total'] ?? 0;
    }

    public function getAllUserSubscriptions($limit, $offset, $search = null)
    {
        $builder = $this->db->table($this->table . ' us');

        $builder->select('
            us.*,
            u.us_Name,
            u.us_Email,
            sp.sp_plan_name,
            sp.sp_validity
        ');
        $builder->join('user u', 'u.us_Id = us.cust_Id', 'left');
        $builder->join('subscription_plan sp', 'sp.sp_Id = us.sp_Id', 'left');
        $builder->where('us.usersub_status !=', 9);
        if (!empty($search)) {
            $builder->groupStart()
                ->like('u.us_Name', $search)
                ->orLike('u.us_Email', $search)
                ->orLike('sp.sp_plan_name', $search)
                ->orLike('us.usersub_expiry', $search)
                ->groupEnd();
        }
        $total = $builder->countAllResults(false);
        $subscriptions = $builder
            ->orderBy('us.usersub_Id', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();

        return [
            'subscriptions' => $subscriptions,
            'total' => $total
        ];
    }
    public function getUserSubscriptionById($id)
    {
        return $this->db->table($this->table . ' us')
            ->select('
                us.*,
                u.us_Name,
                u.us_Email,
                sp.sp_plan_name,
                sp.sp_validity
            ')
            ->join('user u', 'u.us_Id = us.cust_Id', 'left')
            ->join('subscription_plan sp', 'sp.sp_Id = us.sp_Id', 'left')
            ->where('us.usersub_Id', $id)
            ->where('us.usersub_status !=', 9)
            ->get()
            ->getRowArray();
    }
}