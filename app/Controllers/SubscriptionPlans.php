<?php

namespace App\Controllers;
use App\Models\CartModel;
use App\Models\Admin\LeaderboardModel;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\GameMappingModel;
use App\Models\Admin\SubscriptionModel;
use App\Models\Admin\UserSubscriptionsModel;
use App\Models\Admin\TransactionsModel;
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
        $this->UserSubscriptionsModel = new UserSubscriptionsModel();
        $this->transactionModel = new TransactionsModel();
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

    // public function savePayment()
    // {
    //     $paymentId = $this->request->getPost('razorpay_payment_id');
    //     $planId = $this->request->getPost('plan_id');
    //     $amount = $this->request->getPost('amount');
    //     $tokens = $this->request->getPost('token');
    //     $userId = session()->get('user_id');

    //     if (!$paymentId) {
    //         return $this->response->setJSON(['status' => 'error']);
    //     }

    //     // Fetch Plan Details
    //     $plan = $this->UserSubscriptionsModel->where('sp_Id', $planId)->first();

    //     // Subscription expiry (auto calculation)
    //     $expiry = date('Y-m-d H:i:s', strtotime("+{$plan['sp_validity']}"));

    //     // Save to user subscription table
    //     $this->UserSubscriptionsModel->insert([
    //         'cust_Id' => $userId,
    //         'sp_Id' => $planId,
    //         'usersub_amount' => $amount,
    //         'usersub_token' => $tokens,
    //         'usersub_payment_id' => $paymentId,
    //         'usersub_status' => 1,
    //         'usersub_expiry' => $expiry,
    //     ]);

    //     return $this->response->setJSON(['status' => 'success']);
    // }


    public function savePayment()
    {
        $paymentId = $this->request->getPost('razorpay_payment_id');
        $planId = $this->request->getPost('plan_id');
        $amount = (float) $this->request->getPost('amount');
        $tokens = $this->request->getPost('token');
        $userId = session()->get('user_id');

        if (!$paymentId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Payment ID missing']);
        }

        // ✅ Fetch plan from SubscriptionModel (correct model)
        $plan = $this->SubscriptionModel->where('sp_Id', $planId)->first();

        if (!$plan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Plan not found']);
        }

        // Calculate expiry based on plan validity
        $expiry = date('Y-m-d H:i:s', strtotime("+{$plan['sp_validity']}"));

        // ----------------------------------------------------
        // 1. Save Transaction
        // ----------------------------------------------------
        $transactionData = [
            'tt_Id' => 0,
            'sp_Id' => $planId,
            'cust_Id' => $userId,
            'payment_method' => 'Razorpay',
            'gateway_transaction_Id' => $paymentId,
            'transaction_amount' => $amount,
            'commission_amount' => 0,
            'net_credited_amount' => $amount,
            'transaction_status' => '1',
            'player_Id' => null,
            'initiated_at' => date('Y-m-d H:i:s'),
            'completed_at' => date('Y-m-d H:i:s'),
        ];

        $transactionId = $this->transactionModel->insert($transactionData);

        // ----------------------------------------------------
        // 2. Save Subscription
        // ----------------------------------------------------
        $this->UserSubscriptionsModel->insert([
            'transaction_Id' => $transactionId,
            'cust_Id' => $userId,
            'sp_Id' => $planId,
            'usersub_amount' => $amount,
            'usersub_token' => $tokens,
            'usersub_payment_id' => $paymentId,
            'usersub_status' => '1',
            'usersub_expiry' => $expiry,
            'usersub_created_by' => $userId,
            'usersub_created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Payment & subscription saved successfully'
        ]);
    }

    public function createOrder()
    {
        $amount = (float) $this->request->getPost('amount');

        $keyId = "rzp_test_xxxxxxxxxxxx";
        $keySecret = "xxxxxxxxxxxxxxxxxxxx";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.razorpay.com/v1/orders");
        curl_setopt($ch, CURLOPT_USERPWD, "$keyId:$keySecret");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $payload = json_encode([
            'amount' => $amount * 100,
            'currency' => 'INR',
            'payment_capture' => 1
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);
        curl_close($ch);

        return $this->response->setJSON(json_decode($response, true));
    }


}

