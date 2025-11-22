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
}
