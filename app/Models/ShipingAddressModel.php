<?php
namespace App\Models;

use CodeIgniter\Model;

class ShipingAddressModel extends Model
{
    protected $table = 'shipping_address';
    protected $primaryKey = 'shipping_add_Id';
    protected $allowedFields = [
        'shipping_add_Name',
        'shipping_add_LastName',
        'shipping_add_BuildingNo',
        'shipping_add_Landmark',
        'shipping_add_Street',
        'shipping_add_City',
        'shipping_add_State',
        'shipping_add_Default',
        'shipping_add_Pincode',
        'shipping_add_Phone',
        'shipping_add_Email',
        'shipping_add_CustId',
        'shipping_add_Status',
        'shipping_add_createdon',
        'shipping_add_createdby',
        'shipping_add_modifyby',
        'shipping_add_modifyon',
        'shipping_add_Country'
    ];
}