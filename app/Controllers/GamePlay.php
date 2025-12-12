<?php

namespace App\Controllers;
use App\Models\Admin\PlayersModel;

class GamePlay extends BaseController
{
    protected $session;
    protected $db;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->db      = \Config\Database::connect();
        $this->playerModel = new PlayersModel();
    }

    public function play($folderName = null)
    {
        if (!$folderName) {
            return redirect()->to('/game_arena');
        }
        $userId = $this->session->get('user_id');
        if (!$userId) {
            return view('game_play', [
                'folderName' => $folderName,
                'mode' => 'demo'
            ]);
        }
        $gameId = $this->request->getGet('game_id');

        if (!$gameId) {
            return redirect()->back()->with('error', 'Game ID missing.');
        }
        $game = $this->db->table('game')
                         ->where('game_Id', $gameId)
                         ->get()
                         ->getRowArray();

        if (!$game) {
            return redirect()->back()->with('error', 'Game not found.');
        }

        $requiredToken = $game['game_token'];
        $wallet = $this->db->table('user_wallet')
                            ->where('cust_Id', $userId)
                            ->get()
                            ->getRowArray();

        if (!$wallet || $wallet['uw_total_token'] < $requiredToken) {
            return redirect()->back()->with('error', 'Not enough tokens.');
        }
        $newBalance = $wallet['uw_total_token'] - $requiredToken;

        $this->db->table('user_wallet')
                 ->where('cust_Id', $userId)
                 ->update(['uw_total_token' => $newBalance]);
        return view('game_play', [
            'folderName' => $folderName,
            'mode' => 'full',
            'remainingToken' => $newBalance
        ]);
    }
    // -------------------------------Api---------------------------------
    public function saveScore()
{
    $userId = session()->get('user_id');

    if (!$userId) {
        return $this->response->setJSON([
            'status' => false,
            'message' => 'User not logged in'
        ]);
    }

    $json = $this->request->getJSON(true);

    $gameId = $json['game_id'] ?? null;
    $score  = $json['score'] ?? null;
    $time   = $json['time'] ?? 0;  // <-- store default 0 if missing

    if (!$gameId || !$score) {
        return $this->response->setJSON([
            'status' => false,
            'message' => 'game_id and score are required'
        ]);
    }

    $playerModel = new \App\Models\PlayersModel();

    $data = [
        'game_Id' => $gameId,
        'cust_Id' => $userId,
        'player_date' => date('Y-m-d'),
        'player_score' => $score,
        'player_time' => $time,  // <-- store time
        'player_rank' => 0,
        'player_winning_status' => 0,
        'player_status' => 1,
        'player_created_at' => date('Y-m-d H:i:s'),
        'player_created_by' => $userId
    ];

    $playerModel->insert($data);

    return $this->response->setJSON([
        'status' => true,
        'message' => 'Score saved successfully',
        'data' => $data
    ]);
}



}

