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
}
