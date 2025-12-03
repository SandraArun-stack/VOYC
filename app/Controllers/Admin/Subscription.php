<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\SubscriptionModel;

class Subscription extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->model = new SubscriptionModel();
    }

    public function index()
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to('admin');
        }
        $template = view('Admin/common/header');
		$template .= view('Admin/common/leftmenu');
        $template .= view('Admin/subscriptionlist');
		$template .= view('Admin/common/footer');
		$template .= view('Admin/page_scripts/subscriptionjs');
        return $template;
    }
    public function ajaxList()
    {
        $model = new SubscriptionModel(); 
        $data = $model->getDatatables();
        $total = $model->countAll();
        $filtered = $model->countFiltered();

        foreach ($data as &$row) {

            if (!empty($row['sp_plan_name'])) {
                if ($row['sp_plan_name'] === strtoupper($row['sp_plan_name'])) {
                    $row['sp_plan_name'] = $row['sp_plan_name'];
                } else {
                    $row['sp_plan_name'] = ucwords(strtolower($row['sp_plan_name']));
                }
            } else {
                $row['sp_plan_name'] = 'N/A';
            }
            $row['sp_amount'] = !empty($row['sp_amount']) ? (int)$row['sp_amount'] : '0';
            $row['sp_validity'] = !empty($row['sp_validity']) ? $row['sp_validity'] : '0';
            $row['sp_token'] = !empty($row['sp_token']) ? $row['sp_token'] : 'N/A';
            if (!empty($row['sp_discount'])) {
                $discount = (int)$row['sp_discount'];
                $row['sp_discount'] = $discount . '%';
            } else {
                $row['sp_discount'] = '0%';
            }
            $row['actions'] = '<a href="' . base_url('admin/subscription/edit/' . $row['sp_Id']) . '" title="Edit">
                <i class="bi bi-pencil-square"></i>
            </a>';
        }

        return $this->response->setJSON([
            'draw' => intval($this->request->getPost('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data
        ]);
    }
    public function add()
    {
        $template = view('Admin/common/header');
		$template .= view('Admin/common/leftmenu');
        $template .= view('Admin/subscriptionlist');
		$template .= view('Admin/common/footer');
		$template .= view('Admin/page_scripts/subscriptionjs');
        return $template;
    }
    public function edit($id)
    {
        $model = new SubscriptionModel();
        $subscription = $model->find($id);
        if (!$subscription) {
            return redirect()->to('admin/subscription')
                ->with('error', 'Subscription not found.');
        }
        $template = view('Admin/common/header');
		$template .= view('Admin/common/leftmenu');
        $template .= view('Admin/subscription', ['subscription' => $subscription]);
		$template .= view('Admin/common/footer');
		$template .= view('Admin/page_scripts/subscriptionjs');
        return $template;
    }
    public function save()
    {
        $model = new SubscriptionModel();

        $id = $this->request->getPost('subscription_id');
        $data = [
            'sp_plan_name' => $this->request->getPost('plan_name'),
            'sp_amount' => $this->request->getPost('amount'),
            'sp_validity' => $this->request->getPost('validity'),
            'sp_discount' => $this->request->getPost('discount'),
            'sp_token' => $this->request->getPost('token'),
        ];

        if ($id) {
            $model->update($id, $data);
            $message = 'Subscription updated successfully';
        } else {
            $model->insert($data);
            $message = 'Subscription added successfully';
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => $message
        ]);
    }
    public function getAllSubscriptions()
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
        $data = $this->model->getAllSubscriptions($pageSize, $offset, $search);
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Subscriptions fetched successfully.',
            'data'    => $data['subscriptions'],
            'total'   => $data['total']
        ]);
    }
    public function getById($id = null)
    {
        if (empty($id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Subscription ID is required'
            ]);
        }

        $subscription = $this->model->getSubscriptionById($id);

        if (!$subscription) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Subscription not found'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Subscription fetched successfully',
            'data'    => $subscription
        ]);
    }

}
