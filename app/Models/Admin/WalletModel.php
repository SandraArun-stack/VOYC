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
        'uw_expiry',
        'uw_tokens',
        'uw_additional_token',
        'uw_status',
        'uw_created_by',
        'uw_created_at',
        'uw_updated_by',
        'uw_updated_at'
    ];

    public function getDatatables($search = null, $start = 0, $length = 10, $orderBy = 'uw_Id', $orderDir = 'DESC')
    {
        $builder = $this->db->table($this->table)
            ->select("user_wallet.*, customer.cust_name")
            ->join("customer", "customer.cust_Id = user_wallet.cust_Id", "left");

        if (!empty($search)) {
            $builder->groupStart()
                ->like("customer.cust_name", $search)
                ->orLike("user_wallet.uw_tokens", $search)
                ->orLike("user_wallet.uw_additional_token", $search)
                ->orLike("user_wallet.uw_purchased_token", $search)
                ->groupEnd();
        }

        $total = $this->db->table($this->table)
            ->countAllResults();

        $filteredBuilder = $this->db->table($this->table)
            ->join("customer", "customer.cust_Id = user_wallet.cust_Id", "left");

        if (!empty($search)) {
            $filteredBuilder->groupStart()
                ->like("customer.cust_name", $search)
                ->orLike("user_wallet.uw_tokens", $search)
                ->orLike("user_wallet.uw_additional_token", $search)
                ->orLike("user_wallet.uw_purchased_token", $search)
                ->groupEnd();
        }

        $filtered = $filteredBuilder->countAllResults();

        $builder->orderBy($orderBy, $orderDir)
            ->limit($length, $start);

        $result = $builder->get()->getResultArray();

        return [
            'data' => $result,
            'total' => $total,
            'filtered' => $filtered
        ];
    }
}
