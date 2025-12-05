<?php
namespace App\Models;

use CodeIgniter\Model;
use App\Models\CartModel;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\GameMappingModel;

class UserleaderboardModel extends Model
{
    
    protected $table = 'leaderboard';
    protected $primaryKey = 'lb_Id';
    protected $allowedFields = [
        'cust_Id',
        'game_Id',
        'lb_date',
        'lb_score',
        'lb_rank',
        'lb_discount',
        'lb_status',
        'player_Id',
        'lb_created_by',
        'lb_created_at',
        'lb_updated_by',
        'lb_updated_at'
    ];
}