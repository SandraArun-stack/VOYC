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

    public function subscriptionlist()
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to('admin');
        }

        echo view('Admin/common/header');
        echo view('Admin/common/leftmenu');
        echo view('Admin/subscriptionlist');
        echo view('Admin/common/footer');
    }

    public function index($id = null)
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to('admin');
        }

        $data = [];

        if ($id) {
            $data['subscription'] = $this->model->find($id);
        }

        echo view('Admin/common/header');
        echo view('Admin/common/leftmenu');
        echo view('Admin/subscription', $data);
        echo view('Admin/common/footer');
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
