<?php
namespace App\Controllers;

use App\Models\AllCustProductModel;
use CodeIgniter\Controller;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\GameMappingModel;
use App\Models\CartModel;
class AllCustProduct extends Controller
{
    protected $session;
    protected $request;
    protected $AllCustProductModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->AllCustProductModel = new AllCustProductModel();
        $this->PlayersModel = new PlayersModel();
        $this->GameMappingModel = new GameMappingModel();
         $this->CartModel = new CartModel();
    }

    public function index($userId = null)
    {
        $session = session();
        $userId = $session->get('user_id');
        $cartCount = $this->CartModel->getCartItemCount($userId);

        //leaderboard Count
        $today = date('Y-m-d');
        $todayLimit = $this->GameMappingModel->getTodayLeaderboardCount($today);
        $todayLimit = intval($todayLimit);

        $result = $this->PlayersModel->getTodayPlayers($today, $todayLimit, session()->get('user_id'));


        $perPage = 18;

        $customProducts = $this->AllCustProductModel->getAllCustomProducts($perPage);
        $pager = $this->AllCustProductModel->pager;

        $data = [
            'customizable_products' => $customProducts,
            'pager' => $pager
        ];

        return view('common/header', [
            'cartCount' => $cartCount,
            'players' => $result['players'],
            'lastPlayer' => $result['lastPlayer']
        ])
            . view('all_cust_products', $data)
            . view('common/footer')
            . view('pagescripts/all_cust_productsjs');
    }




}
