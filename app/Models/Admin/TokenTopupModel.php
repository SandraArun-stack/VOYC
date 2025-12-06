<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class TokenTopupModel extends Model
{
    protected $table = 'token_topup';
    protected $primaryKey = 'tt_Id';
    protected $allowedFields = [
        'cust_Id',
        'tt_amount',
        'tt_count',
        'tt_created_at',
        'tt_created_by'
    ];

}