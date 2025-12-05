<?php

namespace App\Controllers;
use App\Models\NewProductModel;
use App\Models\HomeModel;
use App\Models\CartModel;
use App\Models\MyWalletModel;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\GameMappingModel;
class MyWallet extends BaseController
{
    protected $HomeModel;
    protected $categories;
    protected $session;
    protected $request;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->productdisplayModel = new HomeModel();
        $this->reviewModel = new NewProductModel();
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

        return view('common/header', [
            'cartCount' => $cartCount,
            'players' => $result['players'],
            'lastPlayer' => $result['lastPlayer']
        ])
            . view('common/UserSideBar')
            . view('my_wallet')
            . view('common/footer')
            . view('pagescripts/mywalletjs');
    }
    public function walletListAjax()
    {
        $model = new MyWalletModel();
        $userId = session()->get('user_id');

        $data = $model->getDatatables($userId);
        $total = $model->countAll($userId);
        $filtered = $model->countFiltered($userId);

        $currentDate = date('Y-m-d');

        foreach ($data as &$row) {

            $row['plan_name'] = $row['plan_name'] ?? 'N/A';
            $row['validity'] = !empty($row['plan_validity'])
                ? $row['plan_validity']
                : 'N/A';
            $expiryRaw = $row['usersub_expiry'] ?? null;

            $row['usersub_expiry'] = !empty($expiryRaw)
                ? date('d-m-Y', strtotime($expiryRaw))
                : 'N/A';


            $row['uw_subscription_token'] = $row['uw_subscription_token'] ?? 0;
            $row['uw_purchased_token'] = $row['uw_purchased_token'] ?? 0;
            $row['uw_bonus_token'] = $row['uw_bonus_token'] ?? 0;

            if (isset($row['usersub_status']) && $row['usersub_status'] == 0) {

                $row['status'] = '<span class="badge p-2 btn-secondary">Pending</span>';

            } elseif (isset($row['usersub_status']) && $row['usersub_status'] == 1) {

                if (!empty($expiryRaw) && strtotime($expiryRaw) >= strtotime($currentDate)) {
                    $row['status'] = '<span class="badge badge-success subscription_badge justify-content-center">Active</span>';
                } else {
                    $row['status'] = '<span class="badge badge-danger subscription_badge justify-content-center">Expired</span>';
                }

            } else {
                $row['status'] = '<span class="badge badge-danger subscription_badge justify-content-center">Expired</span>';
            }
        }

        return $this->response->setJSON([
            "draw" => intval($this->request->getPost('draw')),
            "recordsTotal" => intval($total),
            "recordsFiltered" => intval($filtered),
            "data" => $data
        ]);
    }





    public function getUserTokens()
    {
        $userId = session()->get('user_id');
        $model = new MyWalletModel();

        $tokens = $model->getTotalTokens($userId);

        return $this->response->setJSON(['tokens' => $tokens]);
    }


}

