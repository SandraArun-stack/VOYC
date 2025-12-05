<?php
namespace App\Controllers\Admin;
 
use App\Controllers\BaseController;
use App\Models\Admin\DailyCounterModel;
use App\Models\Admin\GamesModel;
use App\Models\Admin\CustomerModel;
 
class DailyCounter extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->model = new DailyCounterModel();
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
        $template .= view('Admin/dailycounterlist');
		$template .= view('Admin/common/footer');
	    $template .= view('Admin/page_scripts/dailycounterjs');
        return $template;
    }
    public function ajaxList()
    {
        $model = new DailyCounterModel();
        $data = $model->getDatatables();
        $total = $model->countAll();
        $filtered = $model->countFiltered();

        foreach ($data as &$row) {
            if (!empty($row['dgc_date'])) {
                $row['dgc_date'] = date('d-m-Y', strtotime($row['dgc_date']));
            } else {
                $row['dgc_date'] = 'N/A';
            }

            $row['game_name'] = !empty($row['game_name']) ? ucwords(strtolower($row['game_name'])) : 'N/A';
            $row['dgc_player_count'] = $row['dgc_player_count'] ?? '0';
            $row['dgc_winner_count'] = $row['dgc_winner_count'] ?? '0';
            $row['dgc_winning_percentage'] = isset($row['dgc_winning_percentage'])
            ?(int) $row['dgc_winning_percentage'] . '%'
            : '0%';
        }

        return $this->response->setJSON([
            'draw' => intval($this->request->getPost('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data
        ]);
    }
}