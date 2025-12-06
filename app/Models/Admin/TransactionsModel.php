<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class TransactionsModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'transaction_Id';
    protected $allowedFields = [
        'tt_Id',
        'sp_Id',
        'cust_Id',
        'payment_method',
        'gateway_transaction_Id',
        'transaction_amount',
        'commission_amount',
        'net_credited_amount',
        'transaction_status',
        'player_Id',
        'initiated_at',
        'completed_at'
    ];
    public function getDatatables()
    {
        $postData    = service('request')->getPost();
        $searchValue = trim($postData['search']['value'] ?? '');
        $searchValue = preg_replace('/\s+/', '', $searchValue);

        $builder = $this->db->table('transactions t');

        $builder->select("
            t.transaction_Id,
            t.payment_method,
            t.transaction_amount,
            t.transaction_status,
            t.initiated_at,
            c.cust_Name
        ");

        $builder->join('customer c', 'c.cust_Id = t.cust_Id', 'left');

        if (!empty($searchValue)) {
            $escaped = $this->db->escapeLikeString($searchValue);

            $builder->groupStart();
            $builder->where("REPLACE(c.cust_Name,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(t.payment_method,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(t.transaction_status,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(t.transaction_amount,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->groupEnd();
        }

        $builder->orderBy('t.initiated_at', 'DESC');

        if ($postData['length'] != -1) {
            $builder->limit($postData['length'], $postData['start']);
        }

        return $builder->get()->getResultArray();
    }
    public function countAll()
    {
        return $this->db->table('transactions')->countAllResults();
    }
    public function countFiltered()
    {
        $postData    = service('request')->getPost();
        $searchValue = trim($postData['search']['value'] ?? '');
        $searchValue = preg_replace('/\s+/', '', $searchValue);

        $builder = $this->db->table('transactions t');
        $builder->select('COUNT(*) as total');

        $builder->join('customer c', 'c.cust_Id = t.cust_Id', 'left');

        if (!empty($searchValue)) {
            $escaped = $this->db->escapeLikeString($searchValue);

            $builder->groupStart();
            $builder->where("REPLACE(c.cust_Name,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(t.payment_method,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(t.transaction_status,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(t.transaction_amount,' ','') LIKE '%{$escaped}%'", null, false);
            $builder->groupEnd();
        }

        return $builder->get()->getRow()->total;
    }

}