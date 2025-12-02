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
        // $this->model = new GamesModel();
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

        $gameId = $this->request->getPost('game_id');
        $date = $this->request->getPost('date');

        if (!$gameId || !$date) {
            return redirect()->back()->with('error', 'Please fill all required fields');
        }

        $data = [
            'game_Id' => $gameId,
            'gm_date' => $date,
            'gm_status' => 1,
            'gm_created_by' => session()->get('admin_id'),
            'gm_created_at' => date('Y-m-d H:i:s'),
        ];

        $model->insert($data);

        return redirect()->to(base_url('admin/game-details'))
            ->with('success', 'Game mapping saved successfully');
    }

    public function saveGame()
    {
        $data = $this->request->getJSON(true);
        $us_Id = $data['us_Id'] ?? null;

        if (!$us_Id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User ID is required'
            ]);
        }

        if (!$us_Id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }

        $data = $this->request->getJSON(true);
        $game_Id = $data['game_Id'] ?? null;
        $gameData = [
            'game_name' => $data['game_name'] ?? '',
            'game_details' => $data['game_details'] ?? '',
            'game_status' => 1
        ];
        if (empty($gameData['game_name']) || empty($gameData['game_details'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Game name and details are required'
            ]);
        }

        $gameModel = new GamesModel();
        if ($game_Id) {
            $gameData['game_updated_by'] = $us_Id;
            $gameData['game_updated_at'] = date('Y-m-d H:i:s');

            $gameModel->update($game_Id, $gameData);
            $gameData['game_Id'] = $game_Id;

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Game updated successfully',
                'data' => [
                    'game_Id' => $game_Id,
                    'game_name' => $gameData['game_name'],
                    'game_details' => $gameData['game_details'],
                    'game_status' => $gameData['game_status'],
                    'game_updated_by' => $gameData['game_updated_by'],
                    'game_updated_at' => $gameData['game_updated_at'],
                ]
            ]);

        } else {
            $gameData['game_created_by'] = $us_Id;
            $gameData['game_created_at'] = date('Y-m-d H:i:s');

            $insertedId = $gameModel->insert($gameData);
            $gameData['game_Id'] = $insertedId;

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Game created successfully',
                'data' => [
                    'game_Id' => $insertedId,
                    'game_name' => $gameData['game_name'],
                    'game_details' => $gameData['game_details'],
                    'game_status' => $gameData['game_status'],
                    'game_created_by' => $gameData['game_created_by'],
                    'game_created_at' => $gameData['game_created_at'],
                ]
            ]);

        }
    }
    public function getAllGames()
    {
        $pageIndex = (int) $this->request->getGet('pageIndex');
        $pageSize = (int) $this->request->getGet('pageSize');
        $search = $this->request->getGet('search');

        if ($pageSize <= 0) {
            $pageSize = 10;
        }

        $offset = $pageIndex * $pageSize;

        $gameModel = new GamesModel();
        $data = $gameModel->getAllGames($pageSize, $offset, $search);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Games fetched successfully.',
            'data' => $data['games'],
            'total' => $data['total']
        ]);
    }
    public function getGameById($game_Id)
    {
        if (empty($game_Id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Game ID is required'
            ]);
        }

        $gameModel = new GamesModel();
        $game = $gameModel->getGameById($game_Id);

        if (!$game) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Game not found'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Game fetched successfully',
            'data' => $game
        ]);
    }
    public function deleteGameById($game_Id)
    {
        if (empty($game_Id)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Game ID is required'
            ]);
        }

        $gameModel = new GamesModel();
        $game = $gameModel->where('game_Id', $game_Id)
            ->where('game_status !=', '9')
            ->first();

        if (!$game) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Game not found or already deleted'
            ]);
        }
        $gameModel->update($game_Id, [
            'game_status' => '9',
            'game_updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Game deleted successfully',
            'game_Id' => $game_Id
        ]);
    }



}