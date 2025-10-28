<?php
namespace App\Controllers;

use App\Models\MyOrdersModel;
use CodeIgniter\Controller;

class MyOrders extends Controller
{
    protected $session;
    protected $request;
    protected $MyOrdersModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->MyOrdersModel = new MyOrdersModel();
    }

    public function index($userId = null)
    {
        $userId = $this->session->get('user_id');

        if (!$userId) {
            return redirect()->to(base_url('/'));
        }
        $perPage = 4;

        $my_orders = $this->MyOrdersModel->getMyOrders($userId, $perPage);
        $pager = $this->MyOrdersModel->pager;

        return view('common/header')
            . view('common/UserSideBar')
            . view('myorders', ['my_orders' => $my_orders, 'pager' => $pager])
            . view('common/footer')
            . view('pagescripts/myOrdersjs');
    }

    public function saveRating()
    {

        $userId = $this->session->get('user_id');
        $userName = $this->session->get('user_name');

        if (!$userId) {
            return redirect()->to(base_url('/'));
        }

        $order_id = $this->request->getPost('order_id');
        $rating = $this->request->getPost('rating');
        $pr_Id = $this->request->getPost('pr_Id');
        $pri_Id = $this->request->getPost('pri_Id');
        $review = $this->request->getPost('review') ?? null;


        $data = [
            'cust_Id' => $userId,
            'name' => $userName,
            'od_Id' => $order_id,
            'rating' => $rating,
            'pr_Id' => $pr_Id,
            'pri_Id' => $pri_Id,
            'review' => $review,
            'pr_Status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $result = $this->MyOrdersModel->insertRating($data);

        if ($result) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Rating saved successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save rating']);
        }
    }
}