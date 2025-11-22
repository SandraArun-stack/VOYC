<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\GameDetailsViewModel;

class GameDetailsView extends BaseController
{
    protected $model;
    protected $session;

    public function __construct()
    {
        $this->session = session();
        $this->model = new GameDetailsViewModel();
    }

    public function index()
    {
        if (!$this->session->get('ad_uid'))
            return redirect()->to('admin');

        echo view('Admin/common/header');
        echo view('Admin/common/leftmenu');
        echo view('Admin/game_details_view_list'); 
        echo view('Admin/common/footer');
    }

    public function list()
    {
        $rows = $this->model->orderBy('id', 'DESC')->findAll();

        $data = [];
        $i = 1;

        foreach ($rows as $r) {
            $status = $r['status'] == 1
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-danger">Inactive</span>';

            $data[] = [
                $i++,
                $r['user_name'],
                $r['game_id'],
                $r['score'],
                $status,
                '<button class="btn btn-sm btn-primary">Edit</button>'
            ];
        }

        return json_encode(['data' => $data]);
    }
}
