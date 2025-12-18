<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class GamesModel extends Model
{
    protected $table      = 'game';
    protected $primaryKey = 'game_Id';
    protected $allowedFields = [
        'game_name',
        'game_demo_name',
        'game_details',
        'game_status',
        'game_created_by',
        'game_created_at',
        'game_updated_by',
        'game_updated_at'
    ];

    protected $useTimestamps = false; 
       
    public function getAllGames($limit, $offset, $search = null)
    {
        $builder = $this->db->table($this->table);

        $builder->select('game.game_Id,game.game_name,game.game_details,game.game_status,game.game_created_at,game.game_updated_at');

        $builder->where('game.game_status !=', 9);

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('game.game_name', $search)
                    ->orLike('game.game_details', $search)
                    ->groupEnd();
        }

        $total = $builder->countAllResults(false);

        $games = $builder->orderBy('game.game_created_at', 'DESC')
                        ->limit($limit, $offset)
                        ->get()
                        ->getResultArray();

        return [
            'games' => $games,
            'total' => $total
        ];
    }
    public function getGameById($game_Id)
    {
        return $this->where('game_Id', $game_Id)
                    ->where('game_status !=', '9')
                    ->first();
    }

    
    

}
