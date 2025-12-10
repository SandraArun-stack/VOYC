<?php
namespace App\Controllers;

use App\Models\ContactModel;

use App\Models\CartModel;
use CodeIgniter\Controller;
use App\Models\Admin\PlayersModel;
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


        $data = [
            'cartCount' => $cartCount,
            'players' => $result['players'],     
            'lastPlayer' => $result['lastPlayer']
        ];


        return view('common/header', $data)
            . view('contact')
            . view('common/footer')
            . view('pagescripts/contactjs');
    }



}
