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
            if (!empty($row['sp_discount'])) {
                $discount = (int)$row['sp_discount'];
                $row['sp_discount'] = $discount . '%';
            } else {
                $row['sp_discount'] = '0%';
            }
            $row['actions'] = '<a href="' . base_url('admin/subscription/add/' . $row['sp_Id']) . '" class="" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </a>
                &nbsp;
                    <i class="bi bi-trash text-danger" onclick="confirmDelete(' . $row['sp_Id'] . ')" title="Delete"></i>';
        }

        return $this->response->setJSON([
            'draw' => intval($this->request->getPost('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data
        ]);
    }
    public function save()
    {
        $data = $this->request->getJSON(true);
        if (empty($data['sp_plan_name']) || empty($data['sp_validity'])) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Plan name and validity are required'
            ]);
        }
        if (empty($data['sp_Id'])) {
            $data['sp_token'] = bin2hex(random_bytes(16));
            $data['sp_created_at'] = date('Y-m-d H:i:s');
            $data['sp_status'] = 1; 
        } 
        else {
            $data['sp_updated_at'] = date('Y-m-d H:i:s');
        }
        if ($this->model->save($data)) {

            $id = $data['sp_Id'] ?? $this->model->getInsertID();

            return $this->response->setJSON([
                'status'  => true,
                'message' => empty($data['sp_Id']) 
                    ? 'Subscription created successfully' 
                    : 'Subscription updated successfully',
                'data' => $this->model->find($id)
            ]);
        }

        return $this->response->setJSON([
            'status'  => false,
            'message' => 'Subscription save failed'
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
