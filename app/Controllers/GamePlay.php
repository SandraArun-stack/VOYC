<?php

namespace App\Controllers;

class GamePlay extends BaseController
{
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->db      = \Config\Database::connect();
    }

    public function play($folderName = null)
    {
        if (!$folderName) {
            return redirect()->to('/game_arena');
        }

        // Check user login
        $userId = $this->session->get('user_id');

        // DEMO MODE
        if (!$userId) {
            return view('game_play', [
                'folderName' => $folderName,
                'mode' => 'demo'
            ]);
        }

        // FULL MODE (token deduction)
        $gameId = $this->request->getGet('game_id');

        if (!$gameId) {
            return redirect()->back()->with('error', 'Game ID missing.');
        }

        // Get Game
        $game = $this->db->table('game')
                         ->where('game_Id', $gameId)
                         ->get()
                         ->getRowArray();

        if (!$game) {
            return redirect()->back()->with('error', 'Game not found.');
        }

        $requiredToken = $game['game_token'];

        // Get User Wallet
        $wallet = $this->db->table('user_wallet')
                            ->where('cust_Id', $userId)
                            ->get()
                            ->getRowArray();

        if (!$wallet || $wallet['uw_total_token'] < $requiredToken) {
            return redirect()->back()->with('error', 'Not enough tokens.');
        }

        // Deduct token
        $newBalance = $wallet['uw_total_token'] - $requiredToken;

        $this->db->table('user_wallet')
                 ->where('cust_Id', $userId)
                 ->update(['uw_total_token' => $newBalance]);

        // Real game load
        return view('game_play', [
            'folderName' => $folderName,
            'mode' => 'full',
            'remainingToken' => $newBalance
        ]);
    }
}

// <?php

// namespace App\Controllers;
// use App\Models\Admin\GamesModel;
// use App\Models\Admin\GameMappingModel;

// class GamePlay extends BaseController
// {
//     public function play($gameId)
//     {
//         $gamesModel = new GamesModel();
//         $mappingModel = new GameMappingModel();

//         $game = $gamesModel->find($gameId);

//         if (!$game) {
//             return redirect()->to('game_arena');
//         }


//         $todayActive = $mappingModel->where('game_Id', $gameId)
//                                     ->where('gm_date', date('Y-m-d'))
//                                     ->where('gm_status', 1)
//                                     ->first();

//         if (!$todayActive) {
//             return redirect()->to('game_arena')
//                 ->with('error', 'This game is not active right now.');
//         }

//         return view('game_play', ['game' => $game]);
//     }
// }
