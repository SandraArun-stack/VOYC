<?php

namespace App\Controllers;

use App\Models\Admin\CustomerModel;
use App\Models\CartModel;
use CodeIgniter\Controller;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\GameMappingModel;

class DeleteAccount extends BaseController
{
    protected $session;
    protected $customerModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->customerModel = new \App\Models\Admin\CustomerModel();
        $this->CartModel = new CartModel();
        $this->PlayersModel = new PlayersModel();
        $this->GameMappingModel = new GameMappingModel();
    }

    // Load Delete Account Page
    public function index()
    {
        $userId = $this->session->get('user_id');

        if (!$userId) {
            return redirect()->to(base_url('/'));
        }

        $data = [
            'breadcrumb' => 'Delete Account'
        ];
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
            . view('common/UserSideBar', $data)
            . view('delete_account', $data)
            . view('common/footer')
            . view('pagescripts/delete_account_js');
    }

    // Delete Account (Status = 3)
    public function deleteAccount()
    {
        $userId = $this->session->get('user_id');

        if (!$userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'User not logged in'
            ]);
        }

        $updated = $this->customerModel
            ->where('cust_Id', $userId)
            ->set(['cust_Status' => 3, 'cust_modifyon' => date('Y-m-d H:i:s')])
            ->update();

        if ($updated) {
            // clear session
            $this->session->destroy();

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Account deleted successfully'
            ]);
        }

        return $this->response->setJSON([
            'status' => false,
            'message' => 'Failed to delete account'
        ]);
    }
}
