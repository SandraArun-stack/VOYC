<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\GamesModel;
use App\Models\Admin\GameMappingModel;

class Games extends BaseController
{
    protected $model;
    protected $session;

    public function __construct()
    {
        $this->session = session();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        if (!$this->session->get('ad_uid'))
            return redirect()->to('admin');

        echo view('Admin/common/header');
        echo view('Admin/common/leftmenu');
        echo view('Admin/games');
        echo view('Admin/common/footer');
        echo view('Admin/page_scripts/gamesjs');
    }
    public function gameView($id = null)
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to('admin');
        }

        $data = [];

        // $data['games'] = $this-> gameModel->findAll();

        echo view('Admin/common/header');
        echo view('Admin/common/leftmenu');
        echo view('Admin/add_game', $data);
        echo view('Admin/common/footer');
        echo view('Admin/page_scripts/gamesjs');
    }
    public function list_games()
    {
        $gamesModel = new GamesModel();

        $games = $gamesModel->where('game_status !=', 9)
            ->orderBy('game_Id', 'DESC')
            ->findAll();

        $data = [];
        $i = 1;

        foreach ($games as $g) {
            $data[] = [
                $i++,
                date('d-m-Y', strtotime($g['game_created_at'])),
                $g['game_name'],
                '<button class="btn btn-sm btn-primary">Edit</button>'
            ];
        }

        return json_encode(['data' => $data]);
    }
    public function get_games_dropdown()
    {
        $gamesModel = new GamesModel();

        $games = $gamesModel->where('game_status !=', 9)
            ->orderBy('game_name', 'ASC')
            ->findAll();

        return $this->response->setJSON($games);
    }

    public function saveGameMapping()
    {
        $model = new GameMappingModel();
        $game_map_id = $this->request->getPost('gm_Id');
        // print_r($game_map_id);exit();
        $gameId = $this->request->getPost('game_Id');
        $gm_date = $this->request->getPost('gm_date');
        $tokens = $this->request->getPost('tokens');
        $leaderboard_count = $this->request->getPost('leaderboard_count');
        $winning_percentage = $this->request->getPost('winning_percentage');
        $extra_discount_percentage = $this->request->getPost('extra_discount_percentage');
        // Validation
        if (!$gameId || !$gm_date || !$tokens || !$leaderboard_count || !$winning_percentage || !$extra_discount_percentage) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Please Fill All Required Fields'
            ]);
        }

        if ($winning_percentage < 0 || $winning_percentage > 100) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Winning Percentage Must be Between 0 and 100'
            ]);
        }

        if ($extra_discount_percentage < 0 || $extra_discount_percentage > 100) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Discount Percentage Must be Between 0 and 100'
            ]);
        }
        $currentDate = date('Y-m-d');
            if ($gm_date < $currentDate) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'You cannot select an expired date'
                ]);
            }
            if (!$game_map_id) {
            $existingDate = $model
                ->where('gm_date', $gm_date)
                ->first();

            if ($existingDate) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'This date is already mapped. Please choose another date.'
                ]);
            }
        }
        
        $data = [
            'game_Id' => $gameId,
            'gm_date' => $gm_date,
            'gm_tokens' => $tokens,
            'gm_leaderboard_count' => $leaderboard_count,
            'gm_free_tee_percentage' => $winning_percentage,
            'gm_extra_discount' => $extra_discount_percentage,
            'gm_status' => 2,
            'gm_created_by' => session()->get('ad_uid'),
            'gm_created_at' => date('Y-m-d H:i:s'),
        ];

        if ($game_map_id) {
            // Edit: update existing record
            $data['gm_updated_by'] = session()->get('ad_uid');
            $data['gm_updated_at'] = date('Y-m-d H:i:s');

            $model->update($game_map_id, $data);

            $message = 'Game Mapping Updated Successfully';
        } else {
            // Add: insert new record
            $data['gm_created_by'] = session()->get('ad_uid');
            $data['gm_created_at'] = date('Y-m-d H:i:s');

            $model->insert($data);

            $message = 'Game Mapping Saved Successfully';
        }


        return $this->response->setJSON([
            'status' => 'success',
            'message' =>  $message,
            'redirect' => base_url('admin/games')
        ]);
    }

    public function ajaxList()
    {
        $model = new GameMappingModel();

        $start  = $this->request->getPost('start');
        $length = $this->request->getPost('length');

        // ✅ Trim + remove extra spaces for safe search
        $search = trim($this->request->getPost('search')['value'] ?? '');
        $search = preg_replace('/\s+/', '', $search);

        $orderColumnIndex = $this->request->getPost('order')[0]['column'] ?? 0;
        $orderDir = $this->request->getPost('order')[0]['dir'] ?? 'DESC';

        $columns = [
            null,
            'gm_date',
            'game.game_name',
            'gm_tokens',
            'gm_leaderboard_count',
            'gm_free_tee_percentage',
            'gm_extra_discount',
            null
        ];

        $orderBy = $columns[$orderColumnIndex] ?? "gm_Id";

        $data = $model->getDatatables($search, $start, $length, $orderBy, $orderDir);

        // ✅ Format Date + Add % Sign
        foreach ($data['data'] as &$row) {

            // Date → 03-12-2025
            if (!empty($row['gm_date'])) {
                $row['gm_date'] = date('d-m-Y', strtotime($row['gm_date']));
            }

            // Add % sign (remove decimals)
            $row['gm_free_tee_percentage'] = rtrim($row['gm_free_tee_percentage'], '.0') . '%';
            $row['gm_extra_discount']      = rtrim($row['gm_extra_discount'], '.0') . '%';
        }

        return $this->response->setJSON([
            'draw' => intval($this->request->getPost('draw')),
            'recordsTotal' => $data['total'],
            'recordsFiltered' => $data['filtered'],
            'data' => $data['data']
        ]);
    }


    public function edit($id = null)
    {
        $model = new GameMappingModel();

        $game_map_Details = $model->find($id);

        if (!$game_map_Details) {
            return redirect()->to('admin/games')->with('error', 'Record not found');
        }

        $data['game_map_Details'] = $game_map_Details;

        echo view('Admin/common/header');
        echo view('Admin/common/leftmenu');
        echo view('Admin/add_game', $data);
        echo view('Admin/common/footer');
        echo view('Admin/page_scripts/gamesjs');
    }

    public function delete()
    {
        $id = $this->request->getPost('id');
        $model = new \App\Models\Admin\GameMappingModel();

        if (!$id) {
            return $this->response->setJSON([
                'success' => false,
                'msg' => 'Invalid ID'
            ]);
        }

        // Soft delete using model
        $updated = $model->update($id, ['gm_status' => '9']);

        if ($updated) {
            return $this->response->setJSON([
                'success' => true,
                'msg' => 'Game Mapping Deleted Successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'msg' => 'Failed to Delete'
            ]);
        }
    }


}