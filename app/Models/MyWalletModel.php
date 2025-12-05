<?php
namespace App\Models;

use CodeIgniter\Model;

class MyWalletModel extends Model
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
    public function getDatatables($userId)
    {
        $postData = service('request')->getPost();
        $searchValue = trim($postData['search']['value'] ?? '');

        $builder = $this->db->table('user_subscription us');

        $builder->select("
        us.usersub_Id,
        us.usersub_expiry,
        us.usersub_status,

        sp.sp_plan_name AS plan_name,
        sp.sp_validity AS plan_validity,

        COALESCE(uw.uw_subscription_token, 0) AS uw_subscription_token,
        COALESCE(uw.uw_purchased_token, 0) AS uw_purchased_token,
        COALESCE(uw.uw_bonus_token, 0) AS uw_bonus_token
    ");

        $builder->join('subscription_plan sp', 'sp.sp_Id = us.sp_Id', 'left');
        $builder->join('user_wallet uw', 'uw.usersub_Id = us.usersub_Id', 'left');

        // IMPORTANT FIX
        $builder->where('us.cust_Id', $userId);
        $builder->orderBy('us.usersub_created_at', 'DESC');
        if (!empty($searchValue)) {
            $builder->groupStart();
            $builder->like('sp.sp_plan_name', $searchValue);
            $builder->orLike('uw.uw_subscription_token', $searchValue);
            $builder->groupEnd();
        }

        $length = $postData['length'] ?? 10;
        $start = $postData['start'] ?? 0;

        if ($length != -1) {
            $builder->limit($length, $start);
        }

        return $builder->get()->getResultArray();
    }




    public function countAll($userId)
    {
        return $this->db->table('user_subscription')
            ->where('cust_Id', $userId)
            ->countAllResults();
    }

    public function countFiltered($userId)
    {
        $postData = service('request')->getPost();
        $searchValue = trim($postData['search']['value'] ?? '');

        $builder = $this->db->table('user_subscription us')
            ->join('subscription_plan sp', 'sp.sp_Id = us.sp_Id', 'left')
            ->join('user_wallet uw', 'uw.usersub_Id = us.usersub_Id', 'left');

        $builder->where('us.cust_Id', $userId);

        if (!empty($searchValue)) {
            $builder->groupStart();
            $builder->like('sp.sp_plan_name', $searchValue);
            $builder->orLike('uw.uw_subscription_token', $searchValue);
            $builder->groupEnd();
        }

        return $builder->countAllResults();
    }

    public function getTotalTokens($userId)
    {
        $row = $this->db->table('user_wallet uw')
            ->select('uw.uw_total_token AS total_tokens
            ')
            ->where('cust_Id', $userId)
            ->get()
            ->getRow();

        return $row->total_tokens ?? 0;
    }
}
