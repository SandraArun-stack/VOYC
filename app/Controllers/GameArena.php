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

    public function index($gameId)
    {
        $session = session();
        $userId = $session->get('user_id');

        // Fetch today active mapped game ONLY
        $todayGame = $this->GameMappingModel
            ->getTodayActiveGameByGameId($gameId);

        if (!$todayGame) {
            return redirect()->to(base_url('game_arena'))
                ->with('error', 'Game not available today');
        }

        $cartCount = $this->CartModel->getCartItemCount($userId);

        // Leaderboard count
        $today = date('Y-m-d');
        $todayLimit = (int) $this->GameMappingModel
            ->getTodayLeaderboardCount($today);

        $result = $this->PlayersModel
            ->getTodayPlayers($today, $todayLimit, $userId);

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
        $games = $this->GamesModel
            ->whereIn('game_status', [1, 2])
            ->findAll();

        $todayGames = $this->GameMappingModel
            ->select('game_Id')
            ->where('gm_date', date('Y-m-d'))
            ->where('gm_status', 1)
            ->findAll();

        $activeGameIds = array_column($todayGames, 'game_Id');

        return view('common/header', ['lastPlayer' => null])
            . view('all_games', [
                'games' => $games,
                'activeGameIds' => $activeGameIds
            ])
            . view('common/footer');
    }



    public function participate($gameId)
    {
        if (!session()->get('user_id')) {
            return redirect()->to(base_url());
        }

        $userId = session()->get('user_id');

        // Validate today's active game mapping
        $todayGame = $this->GameMappingModel
            ->getTodayActiveGameByGameId($gameId);

        if (!$todayGame) {
            return redirect()->back()
                ->with('error', 'Game is not active today');
        }

        // Set participate mode
        session()->set([
            'game_mode' => 'participate',
            'game_id' => $gameId
        ]);
// echo session()->get('game_mode'); // outputs: participate
// exit;
        $wallet = $this->db->table('user_wallet')
            ->where('cust_Id', $userId)
            ->get()
            ->getRowArray();

        $data = [
            'game' => $todayGame, // IMPORTANT
            'userToken' => $wallet['uw_total_token'] ?? 0,
            'lastPlayer' => null
        ];

        return view('common/header', $data)
            . view('game_participate', $data)
            . view('common/footer');
    }

}
