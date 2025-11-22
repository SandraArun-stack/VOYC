<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\LeaderboardModel;
use App\Models\Admin\GameModel;

class Leaderboard extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->model = new LeaderboardModel();
        $this->gameModel = new GameModel();
    }

    public function leaderboardlist()
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to('admin');
        }

        echo view('Admin/common/header');
        echo view('Admin/common/leftmenu');
        echo view('Admin/leaderboardlist');
        echo view('Admin/common/footer');
    }

    public function leaderboard($id = null)
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to('admin');
        }

        $data = [];
        if ($id) {
            $data['leaderboard'] = $this->model->find($id);
        }

        $data['games'] = $this->gameModel->findAll();

        echo view('Admin/common/header');
        echo view('Admin/common/leftmenu');
        echo view('Admin/leaderboard', $data);
        echo view('Admin/common/footer');
    }

    public function save()
{
    $id = $this->request->getPost('leaderboard_id');
    $gameId = $this->request->getPost('game_id');

    // Get game name
    $game = $this->gameModel->find($gameId);

    $data = [
        'leaderboard_id' => $id,
        'date' => $this->request->getPost('date'),
        'game_id' => $gameId,
        'game_name' => $game['game_name'],
        'turns' => $this->request->getPost('turns'),
    ];

    if ($id) {
        $data['updated_by'] = $this->session->get('ad_uid');
    } else {
        $data['created_by'] = $this->session->get('ad_uid');
    }

    $this->model->save($data);

    return redirect()->to(base_url('admin/leaderboard'))->with('success', 'Saved successfully');
}


    public function ajaxList()
    {
        $list = $this->model->getDatatables();
        $data = [];
        $no = $_POST['start'];

        foreach ($list as $row) {
            $no++;

            $actions = '
                <a href="' . base_url("admin/leaderboard/" . $row['leaderboard_id']) . '" class="btn btn-primary btn-sm">Edit</a>
                <button class="btn btn-danger btn-sm delete" data-id="' . $row['leaderboard_id'] . '">Delete</button>
                <button class="btn btn-warning btn-sm block" data-id="' . $row['leaderboard_id'] . '">Block</button>
            ';

            $data[] = [
                $no,
                $row['date'],
                $row['game_name'],
                $row['winners'],
                $row['turns'],
                $actions
            ];
        }

        return $this->response->setJSON([
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $this->model->countAll(),
            "recordsFiltered" => $this->model->countFiltered(),
            "data" => $data
        ]);
    }

    public function delete()
    {
        $id = $this->request->getPost('id');
        $this->model->update($id, ['status' => 9]);
        return $this->response->setJSON(['status' => true]);
    }

    public function block()
    {
        $id = $this->request->getPost('id');
        $this->model->update($id, ['status' => 2]);
        return $this->response->setJSON(['status' => true]);
    }
}
