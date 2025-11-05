<?php
namespace App\Models;

use CodeIgniter\Model;

class OrderDetailsModel extends Model
{
    protected $table = 'order_detail';
    protected $primaryKey = 'od_Id';
    protected $allowedFields = [
        'or_Id',
        'od_number',
        'pr_Id',
        'od_Quantity',
        'od_Size',
        'od_Color',
        'od_Original_Price',
        'od_Selling_Price',
        'od_DiscountValue',
        'od_DiscountType',
        'od_Status',
        'od_createdon',
        'od_createdby',
        'od_modifyby',
        'od_modifyon',
        'cus_Id',
        'add_Id',
        'tracker_Link',
        'pr_Code',
        'od_Grand_Total',
        'od_Shipping_Address',
        'design_Id'
    ];

    // Insert order items
    public function createOrderItem($data)
    {
        $data['od_Status'] = $data['od_Status'] ?? 'New';
        $data['od_createdon'] = date('Y-m-d H:i:s');
        $data['od_number'] = $data['order_number'] ?? 'VOYC-' . date('Ymd') . '-' . random_int(10000, 99999);

        return $this->insert($data);
    }
}
