<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\TokenModel;
use App\Models\UserModel;

class Token extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->token = new TokenModel();
        $this->user = new UserModel();
    }

    public function index()
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to('/admin');
        }

        echo view('Admin/common/header');
        echo view('Admin/common/leftmenu');
        echo view('Admin/token');
        echo view('Admin/common/footer');
    }

    public function list()
    {
        $tokens = $this->token
            ->select('user_tokens.*, user.username')
            ->join('user', 'user.user_id = user_tokens.user_id')
            ->findAll();

        $output = [];
        $i = 1;

        foreach ($tokens as $t) {
            $output[] = [
                $i++,
                $t['username'],
                $t['daily_token'],
                $t['bonus_token'],
                $t['purchased_token'],
                '<a class="btn btn-info btn-sm">Edit</a>'
            ];
        }

        return $this->response->setJSON(["data" => $output]);
    }
}
