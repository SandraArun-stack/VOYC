<?php
namespace App\Controllers\Admin;
 
use App\Controllers\BaseController;
use App\Models\Admin\LeaderboardModel;
use App\Models\Admin\GamesModel;
use App\Models\Admin\CustomerModel;
 
class Leaderboard extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->model = new LeaderboardModel();
        $this->gameModel = new GamesModel();
        $this->customerModel = new CustomerModel(); 
    }
    public function index()
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to('admin');
        }
        $template = view('Admin/common/header');
		$template .= view('Admin/common/leftmenu');
        $template .= view('Admin/leaderboardlist');
		$template .= view('Admin/common/footer');
		$template .= view('Admin/page_scripts/leaderboardjs');
        return $template;
    }
    // public function teeWinners()
    // {
    //     if (!$this->session->get('ad_uid')) {
    //         return redirect()->to('admin');
    //     }
    //     $template = view('Admin/common/header');
	// 	$template .= view('Admin/common/leftmenu');
    //     $template .= view('Admin/tee_winners');
    //     $template .= view('Admin/common/footer');
    //     return $template;
    // }
    public function ajaxList()
    {
        $model = new LeaderboardModel();
        $data = $model->getDatatables();
        $total = $model->countAll();
        $filtered = $model->countFiltered();

        foreach ($data as &$row) {

            if (!empty($row['lb_created_at'])) {
                $row['lb_created_at'] = date('d-m-Y', strtotime($row['lb_created_at']));
            } else {
                $row['lb_created_at'] = 'N/A';
            }

            $row['game_name'] = !empty($row['game_name']) ? ucwords(strtolower($row['game_name'])) : 'N/A';
            $row['player']    = !empty($row['player']) ? ucwords(strtolower($row['player'])) : 'N/A';
            $row['lb_score']  = $row['lb_score'] ?? '0';
            $row['lb_rank']   = $row['lb_rank'] ?? '0';
        }

        return $this->response->setJSON([
            'draw'            => intval($this->request->getPost('draw')),
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data
        ]);
    }

 }