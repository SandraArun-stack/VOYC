<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class GameDetailsViewModel extends Model
{
    protected $table = 'game_details_view';  // your table name
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_name',
        'game_id',
        'score',
        'status',
        'created_on'
    ];
}
