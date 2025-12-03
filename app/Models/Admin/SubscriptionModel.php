<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class SubscriptionModel extends Model
{
    protected $table = 'subscription_plan';
    protected $primaryKey = 'sp_Id';

    protected $allowedFields = [
        'sp_plan_name','sp_amount','sp_validity','sp_token','sp_coupon_code','sp_status','sp_created_at','sp_created_by',
        'sp_updated_at','sp_updated_by'	
    ];
    public function getDatatables()
    {
        $postData = service('request')->getPost();
        $searchValue = $postData['search']['value'] ?? '';

        $builder = $this->db->table($this->table);

        $builder->select('*');

        if (!empty($searchValue)) {
            $builder->groupStart();
            $builder->like('sp_plan_name', $searchValue);
            $builder->orLike('sp_amount', $searchValue);
            $builder->orLike('sp_validity', $searchValue);
            $builder->orLike('sp_token', $searchValue);
            $builder->groupEnd();
        }

        if ($postData['length'] != -1) {
            $builder->limit($postData['length'], $postData['start']);
        }
        $builder->orderBy('sp_Id', 'ASC');

        return $builder->get()->getResultArray();
    }

    public function countAll()
    {
        return $this->db->table($this->table)->countAllResults();
    }

    public function countFiltered()
    {
        $postData = service('request')->getPost();
        $searchValue = $postData['search']['value'] ?? '';

        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as total');

        if (!empty($searchValue)) {
            $builder->groupStart();
            $builder->like('sp_plan_name', $searchValue);
            $builder->orLike('sp_amount', $searchValue);
            $builder->orLike('sp_validity', $searchValue);
            $builder->orLike('sp_token', $searchValue);
            $builder->groupEnd();
        }

        return $builder->get()->getRow()->total;
    }
    public function getAllSubscriptions($limit, $offset, $search = null)
    {
        $builder = $this->builder();
        if (!empty($search)) {
            $builder->groupStart()
                ->like('sp_plan_name', $search)
                ->orLike('sp_validity', $search)
                ->orLike('sp_coupon_code', $search)
                ->groupEnd();
        }
        $builder->where('sp_status !=', 9);
        $total = $builder->countAllResults(false);
        $subscriptions = $builder
            ->orderBy('sp_Id', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();

        return [
            'subscriptions' => $subscriptions,
            'total'         => $total
        ];
    }
    public function getSubscriptionById($id)
    {
        return $this->where('sp_Id', $id)
                    ->where('sp_status !=', 9)
                    ->first();
    }
}
