<?php
namespace App\Controllers;

use App\Models\ContactModel;

use App\Models\CartModel;
use CodeIgniter\Controller;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\GamesModel;
use App\Models\Admin\GameMappingModel;

class GameArena extends Controller
{
    protected $session;
    protected $request;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->CartModel = new CartModel();
        $this->PlayersModel = new PlayersModel();
        $this->GameMappingModel = new GameMappingModel();
        $this->GamesModel = new GamesModel();
    }

    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');
        $cartCount = $this->CartModel->getCartItemCount($userId);

        //leaderboard Count
        $today = date('Y-m-d');
        $todayLimit = $this->GameMappingModel->getTodayLeaderboardCount($today);
        $todayLimit = intval($todayLimit);

        $result = $this->PlayersModel->getTodayPlayers($today, $todayLimit, session()->get('user_id'));
        $todayGame = $this->GameMappingModel->getTodayActiveGame();

        $data = [
            'cartCount' => $cartCount,
            'players' => $result['players'],     
            'lastPlayer' => $result['lastPlayer']
        ];


        return view('common/header', $data)
            . view('game_arena',  $todayGame)
            . view('common/footer')
            . view('pagescripts/game_arenajs');
    }



}
// <?php
// namespace App\Controllers;

// use App\Models\Admin\GamesModel;                 // ✅ MISSING IMPORT (FIX)
// use App\Models\Admin\GameMappingModel;    // ✅ You are using Admin version
// use App\Models\CartModel;
// use App\Models\Admin\PlayersModel;
// use CodeIgniter\Controller;

// class GameArena extends Controller
// {
//     protected $session;
//     protected $request;

//     public function __construct()
//     {
//         $this->session = \Config\Services::session();
//         $this->request = \Config\Services::request();
//         $this->CartModel = new CartModel();
//         $this->PlayersModel = new PlayersModel();
//         $this->GameMappingModel = new GameMappingModel();
//     }

//     public function index()
// {
//     $gamesModel   = new \App\Models\Admin\GamesModel();
//     $mappingModel = new \App\Models\Admin\GameMappingModel();

//     $todayGame = $mappingModel->getTodayActiveGame();

//     $activeGame = null;

//     if ($todayGame) {
//         $activeGame = $gamesModel->find($todayGame['game_Id']);
//     }

//     // ✅ SAFE DEFAULTS FOR HEADER
//     $data = [
//         'activeGame' => $activeGame,
//         'lastPlayer' => null,   // ✅ FIX
//         'cartCount'  => 0       // ✅ FIX (also usually used in header)
//     ];

//     return view('common/header', $data)
//         . view('game_arena', $data)
//         . view('common/footer');
// }

// }
