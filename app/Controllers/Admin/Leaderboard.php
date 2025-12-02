<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\LeaderboardModel;

use App\Models\Admin\GameDetailsModel;
use App\Models\Admin\CustomerModel;

use App\Models\Admin\GamesModel;


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

    
    public function teeWinners()
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to('admin');
        }

        echo view('Admin/common/header');
        echo view('Admin/common/leftmenu');
        echo view('Admin/tee_winners');
        echo view('Admin/common/footer');
    }


    public function ajaxList()
    {
        $model = new LeaderboardModel();
        $data = $model->getDatatables();
        $total = $model->countAll();
        $filtered = $model->countFiltered();

        foreach ($data as &$row) {

            $row['lb_date']   = $row['lb_date'] ?? 'N/A';
            $row['game_name'] = $row['game_name'] ?? 'N/A';
            $row['player']    = $row['player'] ?? 'N/A';
            $row['lb_score']  = $row['lb_score'] ?? '0';
            $row['lb_rank']   = $row['lb_rank'] ?? '0';

            // Action Button
            $row['actions'] = '
                <a href="' . base_url('admin/leaderboard/view/' . $row['lb_Id']) . '" title="View">
                    <i class="bi bi-eye"></i>
                </a>&nbsp;
                <i class="bi bi-trash text-danger icon-clickable"
                   onclick="confirmDelete(' . $row['lb_Id'] . ')"></i>
            ';
        }

        return $this->response->setJSON([
            'draw'            => intval($this->request->getPost('draw')),
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data
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
