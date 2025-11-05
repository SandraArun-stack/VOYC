<?php
namespace App\Models;

use CodeIgniter\Model;

class ForgotPasswordModel extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'cust_Id';
    protected $allowedFields = [
        'cust_Id',
        'cust_Name',
        'cust_Email',
        'cust_Password',
        'cust_Status',
        'cust_modifyon',
        'reset_token_expiry',
        'reset_token'
    ];
    public function getActiveUserByEmail($email)
{
    return $this->where('cust_Email', $email)
                ->where('cust_Status', 1)
                ->first();
}

}
