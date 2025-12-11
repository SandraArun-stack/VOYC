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

        $this->db = \Config\Database::connect();
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
        // $todayGame = $this->GameMappingModel->getTodayActiveGame();
        $todayGame = $this->GameMappingModel->getTodayActiveGame();

        $data = [
            'cartCount' => $cartCount,
            'players' => $result['players'],     
            'lastPlayer' => $result['lastPlayer'],
            'todayGame' => $todayGame
        ];
        return view('common/header', $data)
            . view('game_arena')
            . view('common/footer')
            . view('pagescripts/game_arenajs');
    }

    public function allGames()
    {
        $games = $this->GamesModel->whereIn('game_status', [1, 2])->findAll();
        $todayGames = $this->GameMappingModel
            ->where('gm_date', date('Y-m-d'))
            ->where('gm_status', 1)
            ->findAll();
        $activeGameIds = [];
        foreach ($todayGames as $tg) {
            $activeGameIds[] = $tg['game_Id'];
        }
        $lastPlayer = null;
        return view('common/header', [
                'lastPlayer' => $lastPlayer
            ])
            . view('all_games', [
                'games' => $games,
                'activeGameIds' => $activeGameIds  
            ])
            . view('common/footer');
    }
// public function participate($gameId)
// {
    
//     if (!session()->get('user_id')) {
//         return redirect()->to(base_url());
//     }

//     $userId = session()->get('user_id');
//     $game = $this->GamesModel->where('game_Id', $gameId)
//         ->whereIn('game_status', [1, 2])
//         ->first();

//     if (!$game) {
//         return redirect()->back()->with('error', 'Game not found.');
//     }
//     $wallet = $this->db->table('user_wallet')
//         ->where('cust_Id', $userId)
//         ->get()
//         ->getRowArray();

//     $userToken = $wallet['uw_total_token'] ?? 0;

//     $data = [
//         'game' => $game,
//         'userToken' => $userToken
//     ];

//     return view('game_participate', $data);
// }
    public function participate($gameId)
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url());
        }

        $userId = session()->get('user_id');
        $game = $this->GamesModel->where('game_Id', $gameId)
            ->whereIn('game_status', [1, 2])
            ->first();

        if (!$game) {
            return redirect()->back()->with('error', 'Game not found.');
        }
        $wallet = $this->db->table('user_wallet')
            ->where('cust_Id', $userId)
            ->get()
            ->getRowArray();

        $userToken = $wallet['uw_total_token'] ?? 0;
        $lastPlayer = null;

        $data = [
            'game' => $game,
            'userToken' => $userToken,
            'lastPlayer' => $lastPlayer
        ];
        return view('common/header', $data)
            . view('game_participate', $data)
            . view('common/footer');
    }


}
