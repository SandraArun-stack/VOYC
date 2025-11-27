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
        $game_Id = $data['game_id'] ?? null;
        $gameData = [
            'game_name'    => $data['game_name'] ?? '',
            'game_details' => $data['game_details'] ?? '',
            'game_status'  => 1
        ];
        if (empty($gameData['game_name']) || empty($gameData['game_details'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Game name and details are required'
            ]);
        }

        $gameModel = new GameDetailsModel();
        if ($game_Id) {
            $gameData['game_updated_by'] = $us_Id;
            $gameData['game_updated_at'] = date('Y-m-d H:i:s');

            $gameModel->update($game_Id, $gameData);
            $gameData['game_id'] = $game_Id;

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Game updated successfully',
                'data' => [
                    'game_id' => $game_Id,                 
                    'game_name' => $gameData['game_name'],
                    'game_details' => $gameData['game_details'],
                    'game_status' => $gameData['game_status'],
                    'game_updated_by' => $gameData['game_updated_by'],
                    'game_updated_at' => $gameData['game_updated_at'],
                ]
            ]);

        } 
        else {
            $gameData['game_created_by'] = $us_Id;
            $gameData['game_created_at'] = date('Y-m-d H:i:s');

            $insertedId = $gameModel->insert($gameData);
            $gameData['game_id'] = $insertedId;

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Game created successfully',
                'data' => [
                    'game_id' => $insertedId,              
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
        $pageSize  = (int) $this->request->getGet('pageSize');
        $search    = $this->request->getGet('search');

        if ($pageSize <= 0) {
            $pageSize = 10;
        }

        $offset = $pageIndex * $pageSize;

        $gameModel = new GameDetailsModel();
        $data = $gameModel->getAllGames($pageSize, $offset, $search);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Games fetched successfully.',
            'data'    => $data['games'],
            'total'   => $data['total']
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

        $gameModel = new GameDetailsModel();
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
            'data'    => $game
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

        $gameModel = new GameDetailsModel();
        $game = $gameModel->where('game_id', $game_Id)
                        ->where('game_status !=', '9')
                        ->first();

        if (!$game) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Game not found or already deleted'
            ]);
        }
        $gameModel->update($game_Id, [
            'game_status'     => '9',   
            'game_updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Game deleted successfully',
            'game_id' => $game_Id
        ]);
    }



}