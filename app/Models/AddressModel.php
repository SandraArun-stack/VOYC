<?php
namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model
{
    protected $table = 'address';
    protected $primaryKey = 'add_Id';
    protected $allowedFields = [
        'add_Name',
        'add_LastName',
        'add_Email',
        'add_Phone',
        'add_BuildingNo',
        'add_Street',
        'add_Landmark',
        'add_City',
        'add_State',
        'add_Pincode',
        'add_CustId',
        'add_Default',
        'add_Status',
        'add_createdon',
        'add_createdby',
        'add_modifyon',
        'add_modifyby',
        'add_phcode',
        'add_Country'
    ];
    public function getDefaultAddress($custId)
    {
        return $this->where('add_CustId', $custId)->where('add_Default', 1)->first();
    }
    public function insertOrder($data)
    {
        $this->db->table('order_detail')->insert($data);
        return $this->db->insertID(); // return the inserted ID
    }
    public function getAllAddresses($zd_uid)
    {
        return $this->db->table('address')
            ->select('address.*')
            ->where('address.add_CustId', $zd_uid)
            ->where('address.add_Status', 1)
            ->get()
            ->getResultArray();

    }
    public function findAddress($id)
    {
        return $this->where('add_Id', $id)->first();
    }
    public function setDefault($userId, $addressId = 0)
    {
        // Unset all current default addresses
        $this->where('add_CustId', $userId)->set(['add_Default' => 0])->update();

        // Set specific address as default (if ID given)
        if ($addressId > 0) {
            $this->update($addressId, ['add_Default' => 1]);
        }
    }
    public function getExistingAddressofUser($custId)
    {
        return $this->where('add_CustId', $custId)
            ->where('add_Status', 1)
            // ->orderBy('add_Default', 'DESC')
            ->findAll();
    }

    public function getExistingShippingAddressofUser($custId)
    {
        return $this->db->table('shipping_address')
            ->where('shipping_add_CustId', $custId)
            ->where('shipping_add_Status', 1)
            ->get()
            ->getResultArray();
    }


}

