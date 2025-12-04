<?php
namespace App\Controllers;

use App\Models\AllCustProductModel;
use CodeIgniter\Controller;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\GameMappingModel;
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
    }

    public function index($userId = null)
    {
        //leaderboard Count
        $today = date('Y-m-d');
        $todayLimit = $this->GameMappingModel->getTodayLeaderboardCount($today);
        $todayLimit = intval($todayLimit);

        $result = $this->PlayersModel->getTodayPlayers($today, $todayLimit, session()->get('user_id'));

        //get customisible products

        $data['customizable_products'] = $this->AllCustProductModel->getAllCustomProducts();

        return view('common/header', [
            'players' => $result['players'],
            'lastPlayer' => $result['lastPlayer']
        ])
            . view('all_cust_products', $data)
            . view('common/footer')
            . view('pagescripts/all_cust_productsjs');
    }


}
