<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\PlayersModel;

class Players extends BaseController
{
    protected $model;
    protected $session;

    public function __construct()
    {
        $this->session = session();
        $this->model = new PlayersModel();
    }

    public function index()
    {
        if (!$this->session->get('ad_uid'))
            return redirect()->to('admin');

        echo view('Admin/common/header');
        echo view('Admin/common/leftmenu');
        echo view('Admin/players');
        echo view('Admin/common/footer');
        echo view('Admin/page_scripts/playersjs');
    }

    public function ajaxList()
    {
        $model = new PlayersModel();

        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $search = $this->request->getPost('search')['value'];

        $orderColumnIndex = $this->request->getPost('order')[0]['column'] ?? 0;
        $orderDir = $this->request->getPost('order')[0]['dir'] ?? 'DESC';

        $columns = [
            null,
            'player_date',
            'customer.cust_name',
            'game.game_name',
            'player_score',
            'player_winning_status'
        ];

        $orderBy = $columns[$orderColumnIndex] ?? 'player_Id';

        $data = $model->getDatatables($search, $start, $length, $orderBy, $orderDir);

        return $this->response->setJSON([
            'draw' => intval($this->request->getPost('draw')),
            'recordsTotal' => $data['total'],
            'recordsFiltered' => $data['filtered'],
            'data' => $data['data']
        ]);
    }


}
