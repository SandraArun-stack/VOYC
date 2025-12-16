<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserleaderboardModel;
use App\Models\CartModel;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\GameMappingModel;
use App\Models\Admin\GamesModel;
class Userleaderboard extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->CartModel = new CartModel();
        $this->PlayersModel = new PlayersModel();
        $this->GameMappingModel = new GameMappingModel();
        $this->UserleaderboardModel = new UserleaderboardModel();
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

        return view('common/header', [
            'cartCount' => $cartCount,
            'players' => $result['players'],
            'lastPlayer' => $result['lastPlayer']
        ])
            . view('common/UserSideBar')
            . view('user_leaderboard')
            . view('common/footer')
            . view('pagescripts/user_leaderboardjs');
    }

    public function userLeaderboardListAjax()
    {
        $session = session();
        $loggedUserId = $session->get('user_id');

        $postData = $this->request->getPost();
        $model = new UserleaderboardModel();

        $data = $model->getleaderboard();
        $total = $model->countAllUserRows();
        $filtered = $model->countFilteredRows();

        return $this->response->setJSON([
            "draw" => intval($postData['draw']),
            "recordsTotal" => $total,
            "recordsFiltered" => $filtered,
            "loggedUserId" => $loggedUserId,
            "data" => $data
        ]);
    }

    public function myredemption()
    {
        $session = session();
        $userId = $session->get('user_id');

        $cartCount = $this->CartModel->getCartItemCount($userId);

        //leaderboard Count
        $today = date('Y-m-d');
        $todayLimit = $this->GameMappingModel->getTodayLeaderboardCount($today);
        $todayLimit = intval($todayLimit);

        $result = $this->PlayersModel->getTodayPlayers($today, $todayLimit, session()->get('user_id'));

        $userRedemption = $this->UserleaderboardModel->getUserRedemption($userId);

        return view('common/header', [
            'cartCount' => $cartCount,
            'players' => $result['players'],
            'lastPlayer' => $result['lastPlayer']
        ])
            . view('common/UserSideBar')
            . view('my_redemption', [
                'redemptions' => $userRedemption
            ])
            . view('common/footer')
            . view('pagescripts/my_redemptionjs');
    }
}