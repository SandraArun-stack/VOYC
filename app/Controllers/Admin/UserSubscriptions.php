<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\UserSubscriptionsModel;
use App\Models\Admin\CustomerModel;
use App\Models\Admin\SubscriptionModel;

class UserSubscriptions extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->userSub = new UserSubscriptionsModel();
        $this->plan = new SubscriptionModel();
        $this->customerModel = new CustomerModel(); 
    }

    public function index()
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to('admin');
        }
        $template = view('Admin/common/header');
		$template .= view('Admin/common/leftmenu');
        $template .= view('Admin/usersubscriptions');
		$template .= view('Admin/common/footer');
		$template .= view('Admin/page_scripts/usersubscriptionjs');
        return $template;
    }

    public function ajaxList()
    {
        $model    = new UserSubscriptionsModel();
        $data     = $model->getDatatables();
        $total    = $model->countAll();
        $filtered = $model->countFiltered();

        foreach ($data as &$row) {
            if (!empty($row['user_name'])) {
                if ($row['user_name'] === strtoupper($row['user_name'])) {
                    $row['user_name'] = $row['user_name'];
                } else {
                    $row['user_name'] = ucwords(strtolower($row['user_name']));
                }
            } else {
                $row['user_name'] = 'N/A';
            }
            $row['plan_name'] = !empty($row['plan_name']) ? $row['plan_name'] : 'N/A';
            if (!empty($row['usersub_discount'])) {
                $row['usersub_discount'] = (int)$row['usersub_discount'] . '%';
            } else {
                $row['usersub_discount'] = '0%';
            }
            $row['usersub_created_at'] = date('d-m-Y', strtotime($row['usersub_created_at']));
            $row['usersub_expiry']     = date('d-m-Y', strtotime($row['usersub_expiry']));
            $today = date('Y-m-d');
            $expiryDate = date('Y-m-d', strtotime($row['usersub_expiry']));

            if ($expiryDate >= $today) {
                $row['usersub_status'] = 1;
                $row['status_badge']   = '<span class="badge bg-success">Active</span>';
            } else {
                $row['usersub_status'] = 2;
                $row['status_badge']   = '<span class="badge bg-danger">Expired</span>';
            }
        }

        return $this->response->setJSON([
            'draw'            => intval($this->request->getPost('draw')),
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data
        ]);
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
