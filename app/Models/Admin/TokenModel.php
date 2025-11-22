<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class TokenModel extends Model
{
    protected $table = 'user_tokens';
    protected $primaryKey = 'token_id';

    protected $allowedFields = [
        'user_id','daily_token','bonus_token','purchased_token'
    ];
}
