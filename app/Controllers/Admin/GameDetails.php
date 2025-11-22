<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\GameDetailsModel;

class GameDetails extends BaseController
{
    protected $model;
    protected $session;

    public function __construct()
    {
        $this->session = session();
        $this->model = new GameDetailsModel();
    }

    public function index()
    {
        if (!$this->session->get('ad_uid'))
            return redirect()->to('admin');

        echo view('Admin/common/header');
        echo view('Admin/common/leftmenu');
        echo view('Admin/game_details_list');
        echo view('Admin/common/footer');
    }

    public function list()
    {
        $games = $this->model->orderBy('id', 'DESC')->findAll();

        $data = [];
        $i = 1;

        foreach ($games as $g) {
            $data[] = [
                $i++,
                date('d-m-Y', strtotime($g['created_on'])),
                $g['game_name'],
                '<button class="btn btn-sm btn-primary">Edit</button>'
            ];
        }

        return json_encode(['data' => $data]);
    }
}
