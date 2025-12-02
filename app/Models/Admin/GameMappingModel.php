<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class GameMappingModel extends Model
{
    protected $table = 'games_mapping';
    protected $primaryKey = 'gm_id';

    protected $allowedFields = [
        'game_Id',
        'gm_date',
        'gm_status',
        'gm_leaderboard_count',
        'gm_extra_discount',
        'gm_free_tee_percentage',
        'gm_created_by',
        'gm_created_at',
        'gm_updated_by',
        'gm_updated_at'
    ];

    protected $useTimestamps = false;
}
