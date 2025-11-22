<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class SubscriptionModel extends Model
{
    protected $table = 'subscription_plan';
    protected $primaryKey = 'subscription_id';

    protected $allowedFields = [
        'plan_name','rate','validity_days','discount','turns','status'
    ];
}
