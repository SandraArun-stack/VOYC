<?php

namespace App\Controllers;

class GamePlay extends BaseController
{
    public function play()
    {
        return view('game_play');
    }
}
// <?php

// namespace App\Controllers;
// use App\Models\Admin\GamesModel;
// use App\Models\Admin\GameMappingModel;

// class GamePlay extends BaseController
// {
//     public function play($gameId)
//     {
//         $gamesModel = new GamesModel();
//         $mappingModel = new GameMappingModel();

//         $game = $gamesModel->find($gameId);

//         if (!$game) {
//             return redirect()->to('game_arena');
//         }

        
//         $todayActive = $mappingModel->where('game_Id', $gameId)
//                                     ->where('gm_date', date('Y-m-d'))
//                                     ->where('gm_status', 1)
//                                     ->first();

//         if (!$todayActive) {
//             return redirect()->to('game_arena')
//                 ->with('error', 'This game is not active right now.');
//         }

//         return view('game_play', ['game' => $game]);
//     }
// }
