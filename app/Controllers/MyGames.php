<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MyGamesModel;
use App\Models\CartModel;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\GameMappingModel;
class MyGames extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->CartModel = new CartModel();
        $this->PlayersModel = new PlayersModel();
        $this->GameMappingModel = new GameMappingModel();
        $this->MyGamesModel = new MyGamesModel();
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

        return view('common/header', [
            'cartCount' => $cartCount,
            'players' => $result['players'],
            'lastPlayer' => $result['lastPlayer']
        ])
            . view('common/UserSideBar')
            . view('my_games')
            . view('common/footer')
            . view('pagescripts/mygamesjs');
    }

    public function myGamesListAjax()
    {
        $userId = session()->get('user_id');
        $postData = $this->request->getPost();
        $model = new MyGamesModel();

        $data = $model->getUserPlayedGames($userId, $postData);
        $total = $model->countAllUserRows($userId);
        $filtered = $model->countFilteredRows($userId, $postData);

        return $this->response->setJSON([
            "draw" => intval($postData['draw']),
            "recordsTotal" => $total,
            "recordsFiltered" => $filtered,
            "data" => $data
        ]);
    }

}