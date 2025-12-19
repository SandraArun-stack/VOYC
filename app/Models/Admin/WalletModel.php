<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class WalletModel extends Model
{
    protected $table = 'user_wallet';
    protected $primaryKey = 'uw_Id';

    protected $allowedFields = [
        'cust_Id',
        'usersub_Id',
        'sp_Id',
        'uw_expiry',
        'uw_subscription_token',
        'uw_purchased_token',
        'uw_bonus_token',
        'uw_total_token',
        'uw_status',
        'uw_created_by',
        'uw_created_at',
        'uw_updated_by',
        'uw_updated_at'
    ];

    public function getDatatables($search = null, $start = 0, $length = 10, $orderBy = 'uw_Id', $orderDir = 'DESC')
    {
        $search = trim($search);
        $search = strtolower(preg_replace('/\s+/', '', $search));

        $builder = $this->db->table($this->table)
            ->select("
                user_wallet.*,
                customer.cust_Name,
                DATE_FORMAT(user_wallet.uw_expiry, '%d-%m-%Y') AS uw_expiry
            ")
            ->join("customer", "customer.cust_Id = user_wallet.cust_Id", "left");

        if (!empty($search)) {
            $escaped = $this->db->escapeLikeString($search);

            $builder->groupStart()
                ->where("REPLACE(LOWER(customer.cust_Name),' ','') LIKE '%{$escaped}%'", null, false)
                ->orWhere("REPLACE(user_wallet.uw_subscription_token,' ','') LIKE '%{$escaped}%'", null, false)
                ->orWhere("REPLACE(user_wallet.uw_bonus_token,' ','') LIKE '%{$escaped}%'", null, false)
                ->orWhere("REPLACE(user_wallet.uw_purchased_token,' ','') LIKE '%{$escaped}%'", null, false)
                ->orWhere("REPLACE(DATE_FORMAT(user_wallet.uw_expiry,'%d%m%Y'),' ','') LIKE '%{$escaped}%'", null, false)
                ->groupEnd();
        }
        $total = $this->db->table($this->table)->countAllResults();
        $filteredBuilder = $this->db->table($this->table)
            ->join("customer", "customer.cust_Id = user_wallet.cust_Id", "left");

        if (!empty($search)) {
            $escaped = $this->db->escapeLikeString($search);

            $filteredBuilder->groupStart()
                ->where("REPLACE(LOWER(customer.cust_Name),' ','') LIKE '%{$escaped}%'", null, false)
                ->orWhere("REPLACE(user_wallet.uw_subscription_token,' ','') LIKE '%{$escaped}%'", null, false)
                ->orWhere("REPLACE(user_wallet.uw_bonus_token,' ','') LIKE '%{$escaped}%'", null, false)
                ->orWhere("REPLACE(user_wallet.uw_purchased_token,' ','') LIKE '%{$escaped}%'", null, false)
                ->orWhere("REPLACE(DATE_FORMAT(user_wallet.uw_expiry,'%d%m%Y'),' ','') LIKE '%{$escaped}%'", null, false)
                ->groupEnd();
        }

        $filtered = $filteredBuilder->countAllResults();
        $builder->orderBy($orderBy, $orderDir)
            ->limit($length, $start);

        $result = $builder->get()->getResultArray();
        return [
            'data'     => $result,
            'total'    => $total,
            'filtered' => $filtered
        ];
    }
}
