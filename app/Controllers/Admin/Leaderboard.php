<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\LeaderboardModel;
use App\Models\Admin\GameModel;

class Leaderboard extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->model = new LeaderboardModel();
        $this->gameModel = new GameModel();
    }

    public function index()
    {
        return redirect()->to(base_url('admin/leaderboardlist'));
    }

    public function leaderboardlist()
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to(base_url('admin'));
        }

        $template  = view('Admin/common/header');
        $template .= view('Admin/common/leftmenu');
        $template .= view('Admin/leaderboardlist');  
        $template .= view('Admin/common/footer');

        return $template;
    }

    public function leaderboard()
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to(base_url('admin'));
        }

        $data['games'] = $this->gameModel->findAll();

        $template  = view('Admin/common/header');
        $template .= view('Admin/common/leftmenu');
        $template .= view('Admin/leaderboard', $data);
        $template .= view('Admin/common/footer');

        return $template;
    }

    public function save()
    {
        $gameId = $this->request->getPost('game_id');

        // Fetch game name based on game_id
        $game = $this->gameModel->find($gameId);

        $data = [
            'date'      => $this->request->getPost('date'),
            'game_id'   => $gameId,
            'game_name' => $game['game_name'], 
            'winners'   => $this->request->getPost('winners'),
            'turns'     => $this->request->getPost('turns'),
        ];

        $this->model->save($data);

        return redirect()->to(base_url('admin/leaderboard'))
            ->with('success', 'Leaderboard saved successfully');
    }


    public function ajaxList()
    {
        $model = new LeaderboardModel();
        $list = $model->getDatatables();

        $data = [];
        $no = $this->request->getPost('start');

        foreach ($list as $row) {
            $no++;
            $data[] = [
                $no,
                $row['date'],
                $row['game_name'],
                $row['winners'],
                $row['turns'],
                '<button class="btn btn-primary btn-sm edit" data-id="' . $row['leaderboard_id'] . '">Edit</button>
                 <button class="btn btn-danger btn-sm delete" data-id="' . $row['leaderboard_id'] . '">Delete</button>'
            ];
        }

        return $this->response->setJSON([
            "draw" => intval($this->request->getPost('draw')),
            "recordsTotal" => $model->countAll(),
            "recordsFiltered" => $model->countFiltered(),
            "data" => $data
        ]);
    }

    public function edit($id)
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to(base_url('admin'));
        }

        $data['game']  = $this->model->find($id);
        $data['games'] = $this->gameModel->findAll();

        $template  = view('Admin/common/header');
        $template .= view('Admin/common/leftmenu');
        $template .= view('Admin/leaderboard', $data);
        $template .= view('Admin/common/footer');

        return $template;
    }
}
