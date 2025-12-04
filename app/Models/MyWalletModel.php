<?php
namespace App\Models;

use CodeIgniter\Model;

class MyWalletModel extends Model
{
    protected $table = 'user_wallet';
    protected $primaryKey = 'uw_Id';

    protected $allowedFields = [
        'cust_Id',
        'usersub_Id',
        'uw_expiry',
        'uw_tokens',
        'uw_purchased_token',
        'uw_bonus_token',
        'uw_status',
        'uw_created_by',
        'uw_created_at',
        'uw_updated_by',
        'uw_updated_at'
    ];
    public function getDatatables($userId)
    {
        $postData = service('request')->getPost();
        $searchValue = preg_replace('/\s+/', '', $postData['search']['value'] ?? '');

        $builder = $this->db->table('user_wallet uw');
        $builder->select("
            uw.uw_tokens,
            uw.uw_purchased_token,
            uw.uw_bonus_token,
            us.usersub_status,
            us.usersub_expiry,
            sp.sp_plan_name AS plan_name,
            sp.sp_validity   AS plan_validity
        ");

        $builder->join('user_subscription us', 'us.usersub_Id = uw.usersub_Id', 'left');
        $builder->join('subscription_plan sp', 'sp.sp_Id = us.sp_Id', 'left');

        $builder->where('uw.cust_Id', $userId);

        if (!empty($searchValue)) {
            $builder->groupStart();
            $builder->like("REPLACE(sp.sp_plan_name, ' ', '')", $searchValue);
            $builder->orLike("REPLACE(uw.uw_tokens, ' ', '')", $searchValue);
            $builder->groupEnd();
        }
        $length = $postData['length'] ?? 10;
        $start  = $postData['start'] ?? 0;

        if ($length != -1) {
            $builder->limit($length, $start);
        }

        return $builder->get()->getResultArray();
    }
    public function countAll($userId)
    {
        return $this->db->table('user_wallet')
            ->where('cust_Id', $userId)
            ->countAllResults();
    }

    public function countFiltered($userId)
    {
        $postData = service('request')->getPost();
        $searchValue = preg_replace('/\s+/', '', $postData['search']['value'] ?? '');

        $builder = $this->db->table('user_wallet uw');
        $builder->join('user_subscription us', 'us.usersub_Id = uw.usersub_Id', 'left');
        $builder->join('subscription_plan sp', 'sp.sp_Id = us.sp_Id', 'left');

        $builder->where('uw.cust_Id', $userId);

        if (!empty($searchValue)) {
            $builder->groupStart();
            $builder->like("REPLACE(sp.sp_plan_name, ' ', '')", $searchValue);
            $builder->orLike("REPLACE(uw.uw_tokens, ' ', '')", $searchValue);
            $builder->groupEnd();
        }
        return $builder->countAllResults();
    }
    public function getTotalTokens($userId)
    {
        $row = $this->db->table('user_wallet')
            ->select('
                COALESCE(SUM(uw_tokens),0) +
                COALESCE(SUM(uw_purchased_token),0) +
                COALESCE(SUM(uw_bonus_token),0) AS total_tokens
            ')
            ->where('cust_Id', $userId)
            ->get()
            ->getRow();

        return $row->total_tokens ?? 0;
    }
}
