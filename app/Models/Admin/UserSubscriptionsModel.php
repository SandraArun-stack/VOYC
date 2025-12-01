<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class UserSubscriptionsModel extends Model
{
    protected $table = 'user_subscription';
    protected $primaryKey = 'usersub_Id';

    protected $allowedFields = [
        'cust_Id','sp_Id','usersub_expiry','usersub_status','usersub_created_by','usersub_created_at','usersub_updated_by','usersub_updated_at'	
    ];

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
            'total'         => $total
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