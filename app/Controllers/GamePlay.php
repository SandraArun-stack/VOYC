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
            return redirect()->to(base_url('game_arena'));
        }

        $session = session();
        $userId  = $session->get('user_id');
        $gameId  = $this->request->getGet('game_id');
        if (!$userId || !$gameId || $session->get('game_mode') !== 'participate') {
            $session->set('game_mode', 'demo');

            return view('game_play', [
                'folderName' => $folderName,
                'mode'       => 'demo'
            ]);
        }
        $todayGame = $this->db->table('games_mapping')
            ->where('game_Id', $gameId)
            ->where('gm_date', date('Y-m-d'))
            ->where('gm_status', 1)
            ->get()
            ->getRowArray();

        if (!$todayGame) {
            return redirect()->to(base_url('game_arena'))
                ->with('error', 'Game is not active today.');
        }

        // Get user wallet
        $wallet = $this->db->table('user_wallet')
            ->where('cust_Id', $userId)
            ->get()
            ->getRowArray();

        if (!$wallet || $wallet['uw_total_token'] < $todayGame['gm_tokens']) {
            return redirect()->back()
                ->with('error', 'Not enough tokens.');
        }

        // Deduct tokens
        $this->db->table('user_wallet')
            ->where('cust_Id', $userId)
            ->update([
                'uw_total_token' => $wallet['uw_total_token'] - $todayGame['gm_tokens']
            ]);
        $session->remove('game_mode');

        return view('game_play', [
            'folderName' => $folderName,
            'mode'       => 'full'
        ]);
    }
    // -------------------------------Api---------------------------------
    public function saveScore()
    {
        $userId   = session()->get('user_id');
        $gameMode = session()->get('game_mode');
        $gameId   = session()->get('game_id');

        if ($gameMode !== 'participate') {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Demo mode score not saved'
            ]);
        }

        if (!$userId || !$gameId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid session'
            ]);
        }

        $json  = $this->request->getJSON(true);
        $score = $json['score'] ?? null;
        $time  = $json['time'] ?? null;

        if ($score === null || $time === null) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Score and time required'
            ]);
        }

        $playerModel = new \App\Models\PlayersModel();

        $playerModel->insert([
            'game_Id' => $gameId,
            'cust_Id' => $userId,
            'player_date' => date('Y-m-d'),
            'player_score' => $score,
            'player_time' => $time,
            'player_rank' => 0,
            'player_winning_status' => 0,
            'player_status' => 1,
            'player_created_at' => date('Y-m-d H:i:s'),
            'player_created_by' => $userId
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Participate score saved'
        ]);
    }




}

