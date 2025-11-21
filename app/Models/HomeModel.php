<?php
namespace App\Models;

use CodeIgniter\Model;

class HomeModel extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'cust_Id';
    protected $allowedFields = [
        'cust_Name',
        'cust_Email',
        'cust_Password',
        'cust_Phone',
        'cust_Status',
        'cust_createdon',
        'cust_createdby',
        'cust_modifyby',
        'cust_modifyon',
        'auth_type',
        'reset_token_expiry',
        'reset_token'
    ];

    public function registerUser($data)
    {
        if ($this->where('cust_Email', $data['email'])->first()) {
            return ['status' => 'error', 'message' => 'Email Already Registered.'];
        }

        $insertData = [
            'cust_Name' => $data['full_name'],
            'cust_Email' => $data['email'],
            'cust_Password' => $data['password'],
            'cust_Phone' => $data['phone_number'],
            'cust_Status' => 1,
            'cust_createdon' => date('Y-m-d H:i:s'),
            'cust_createdby' => 0,
        ];

        $inserted = $this->insert($insertData);

        if ($inserted) {
            return ['status' => 'success', 'message' => 'Registration successful.', 'user_id' => $inserted];
        } else {
            return ['status' => 'error', 'message' => 'Database error, please try again.'];
        }
    }
    public function loginUser_Model($data)
    {
        $user = $this->where('cust_Email', $data['email'])->first();

        if (!$user) {
            return ['status' => 'error', 'message' => 'Incorrect Email Id or Password'];
        }
        if ($user['cust_Status'] == 2) {
            return ['status' => 'error', 'message' => 'Your Account is Inactive. Please Contact Support.'];
        }
        if ($user['cust_Status'] == 3) {
            return ['status' => 'error', 'message' => 'The Account is No Longer Available.'];
        }

        if ($user['cust_Password'] !== $data['password']) {
            return ['status' => 'error', 'message' => 'Incorrect Email Id or Password'];
        }
        if ($user['cust_Status'] == 1) {
            return [
                'status' => 'success',
                'message' => 'Login successful.',
                'user' => $user
            ];
        }

    }

}
