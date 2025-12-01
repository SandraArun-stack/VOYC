<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\UserSubscriptionsModel;
use App\Models\UserModel;
use App\Models\Admin\SubscriptionModel;

class UserSubscriptions extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->userSub = new UserSubscriptionsModel();
        $this->user = new UserModel();
        $this->plan = new SubscriptionModel();
    }

    public function index()
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to('/admin');
        }

        echo view('Admin/common/header');
        echo view('Admin/common/leftmenu');
        echo view('Admin/usersubscriptions');
        echo view('Admin/common/footer');
    }

    public function list()
    {
        $subs = $this->userSub->select('user_subscription.*, user.username, subscription_plan.plan_name')
            ->join('user', 'user.user_id = user_subscription.user_id')
            ->join('subscription_plan', 'subscription_plan.subscription_id = user_subscription.subscription_id')
            ->findAll();

        $data = [];
        $i = 1;

        foreach ($subs as $s) {
            $data[] = [
                $i++,
                $s['username'],
                $s['start_date'],
                $s['end_date'],
                $s['plan_name'],
                $s['discount'],
                $s['token'],
                '<a href="#" class="btn btn-primary btn-sm">View</a>'
            ];
        }

        return $this->response->setJSON(["data" => $data]);
    }
    public function createSubscribe()
    {
        $data = $this->request->getJSON(true);

        $custId = $data['cust_Id'] ?? null;
        $planId = $data['sp_Id'] ?? null;

        if (!$custId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'cust_Id (User ID) is required.'
            ]);
        }

        if (!$planId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'sp_Id (Subscription Plan ID) is required.'
            ]);
        }

        $today = date('Y-m-d');

        // ✅ CHECK ACTIVE SUBSCRIPTION
        $activeSub = $this->userSub
            ->where('cust_Id', $custId)
            ->where('usersub_status', 1)
            ->where('usersub_expiry >=', $today)
            ->first();

        if ($activeSub) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You already have an active subscription.',
                'data'    => []
            ]);
        }

        // ✅ FETCH PLAN DETAILS
        $plan = $this->plan
            ->where('sp_Id', $planId)
            ->where('sp_status !=', 9)
            ->first();

        if (!$plan) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Subscription plan not found or deleted.'
            ]);
        }

        // ✅ CALCULATE EXPIRY DATE
        try {
            $startDate = new \DateTime($today);
            $expiryDate = clone $startDate;
            $expiryDate->add(new \DateInterval("P{$plan['sp_validity']}D"));
            $usersub_expiry = $expiryDate->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Invalid plan validity.'
            ]);
        }

        // ✅ INSERT PAYLOAD
        $payload = [
            'cust_Id'              => $custId,
            'sp_Id'                => $planId,
            'usersub_expiry'       => $usersub_expiry,
            'usersub_status'       => 1,
            'usersub_created_by'  => $custId,
            'usersub_created_at'  => date('Y-m-d H:i:s'),
            'usersub_updated_by'  => $custId,
            'usersub_updated_at'  => date('Y-m-d H:i:s'),
        ];

        $id = $this->userSub->insert($payload);

        if (!$id) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to create subscription.'
            ]);
        }

        // ✅ SUCCESS RESPONSE
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Subscription created successfully.',
            'data'    => [
                'usersub_Id'    => $id,
                'cust_Id'       => $custId,
                'sp_Id'         => $planId,
                'plan_name'     => $plan['sp_plan_name'],
                'validity_days' => $plan['sp_validity'],
                'expiry_date'   => date('d F Y', strtotime($usersub_expiry))
            ]
        ]);
    }

    public function getAll()
    {
        $pageIndex = (int) $this->request->getGet('pageIndex');
        $pageSize  = (int) $this->request->getGet('pageSize');
        $search    = $this->request->getGet('search');

        if ($pageSize <= 0) {
            $pageSize = 10;
        }

        if ($pageIndex < 0) {
            $pageIndex = 0;
        }

        $offset = $pageIndex * $pageSize;
        $data = $this->userSub->getAllUserSubscriptions($pageSize, $offset, $search);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'User subscriptions fetched successfully.',
            'data'    => $data['subscriptions'],
            'total'   => $data['total']
        ]);
    }
    public function getById($id = null)
    {
        if (empty($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User Subscription ID is required'
            ]);
        }
        $subscription = $this->userSub->getUserSubscriptionById($id);

        if (!$subscription) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User subscription not found'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'User subscription fetched successfully',
            'data'    => $subscription
        ]);
    }
}
