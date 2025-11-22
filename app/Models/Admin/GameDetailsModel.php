<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class GameDetailsModel extends Model
{
    protected $table = 'game_details';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'game_name',
        'created_on',
        'status'
    ];
}
