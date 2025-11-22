<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class UserSubscriptionsModel extends Model
{
    protected $table = 'user_subscription';
    protected $primaryKey = 'user_subscription_id';

    protected $allowedFields = [
        'user_id','subscription_id','start_date','end_date','discount','token','status'
    ];
}
