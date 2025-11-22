<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\DiscountModel;
// use App\Models\Admin\UserModel; // if needed for username

class Discount extends BaseController
{
    protected $model;
    protected $session;

    public function __construct()
    {
        $this->session = session();
        $this->model = new DiscountModel();
        // $this->userModel = new UserModel(); 
    }

    public function index()
    {
        if (!$this->session->get('ad_uid'))
            return redirect()->to('admin');

        echo view('Admin/common/header');
        echo view('Admin/common/leftmenu');
        echo view('Admin/discountlist');
        echo view('Admin/common/footer');
    }

    public function list()
    {
        $discounts = $this->model->findAll();
        $data = [];

        $i = 1;
        foreach ($discounts as $d) {

            $user = $this->userModel->find($d['user_id']);

            $data[] = [
                $i++,
                $user['username'] ?? 'N/A',
                $d['subscription_discount'] . "%",
                $d['additional_discount'] . "%",
                '<button class="btn btn-sm btn-primary">Edit</button>'
            ];
        }

        return json_encode(['data' => $data]);
    }
}
