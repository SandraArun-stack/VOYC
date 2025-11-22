<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class DiscountModel extends Model
{
    protected $table = 'discounts';   // your table name
    protected $primaryKey = 'discount_id';

    protected $allowedFields = [
        'user_id',
        'subscription_discount',
        'additional_discount'
    ];
}
