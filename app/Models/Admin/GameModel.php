<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class GameModel extends Model
{
    protected $table = 'game';
    protected $primaryKey = 'game_id';

    public function getGames()
    {
        return $this->where('status', 1)->findAll();
    }
}
