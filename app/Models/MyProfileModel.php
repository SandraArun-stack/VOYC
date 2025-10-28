<?php
namespace App\Models;

use CodeIgniter\Model;

class MyProfileModel extends Model
{
    // ✅ Use the correct table and primary key
    protected $table = 'customer';
    protected $primaryKey = 'cust_Id';

    // ✅ Select only the needed fields (optional, but cleaner)
    protected $allowedFields = [
        'cust_Name', 'cust_Email', 'cust_Phone', 'cust_phcode', 'cust_Password',
        'cust_Status', 'cust_createdon', 'cust_modifyon'
    ];

    public function getUserById($userId)
    {
        return $this->where('cust_Id', $userId)->first();
    }
}
