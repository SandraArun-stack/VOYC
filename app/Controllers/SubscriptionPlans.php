<?php

namespace App\Controllers;
use App\Models\CartModel;
use App\Models\Admin\LeaderboardModel;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\GameMappingModel;
use App\Models\Admin\SubscriptionModel;
class SubscriptionPlans extends BaseController
{
    protected $session;
    protected $request;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->CartModel = new CartModel();
        $this->LeaderboardModel = new LeaderboardModel();
        $this->PlayersModel = new PlayersModel();
        $this->GameMappingModel = new GameMappingModel();
        $this->SubscriptionModel = new SubscriptionModel();
    }

    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');
        //get cart count
        $cartCount = $this->CartModel->getCartItemCount($userId);
        //leaderboard Count
        $today = date('Y-m-d');
        $todayLimit = $this->GameMappingModel->getTodayLeaderboardCount($today);
        $todayLimit = intval($todayLimit);

        $result = $this->PlayersModel->getTodayPlayers($today, $todayLimit, session()->get('user_id'));

        $plans = $this->SubscriptionModel
            ->where('sp_status', '1')
            ->orderBy('sp_Id', 'ASC')
            ->findAll();

        return view('common/header', [
            'cartCount' => $cartCount,
            'players' => $result['players'],
            'lastPlayer' => $result['lastPlayer'],
            'plans' => $plans
        ])
            . view('common/UserSideBar')
            . view('subscription_plans')
            . view('common/footer')
            . view('pagescripts/subscription_plansjs');
    }

    public function savePayment()
    {
        $paymentId = $this->request->getPost('razorpay_payment_id');
        $planId = $this->request->getPost('plan_id');
        $amount = $this->request->getPost('amount');
        $tokens = $this->request->getPost('token');
        $userId = session()->get('user_id');

        if (!$paymentId) {
            return $this->response->setJSON(['status' => 'error']);
        }

        // Fetch Plan Details
        $plan = $this->SubscriptionModel->where('sp_Id', $planId)->first();

        // Subscription expiry (auto calculation)
        $expiry = date('Y-m-d H:i:s', strtotime("+{$plan['sp_validity']}"));

        // Save to user subscription table
        $this->UserSubscriptionModel->insert([
            'cust_Id' => $userId,
            'sp_Id' => $planId,
            'usersub_amount' => $amount,
            'usersub_token' => $tokens,
            'usersub_payment_id' => $paymentId,
            'usersub_status' => 1,
            'usersub_expiry' => $expiry,
        ]);

        return $this->response->setJSON(['status' => 'success']);
    }



}

