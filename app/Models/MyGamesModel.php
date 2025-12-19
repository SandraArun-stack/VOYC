<?php
namespace App\Models;

use CodeIgniter\Model;


class MyGamesModel extends Model
{

    protected $table = 'players';
    protected $primaryKey = 'player_Id';

    protected $allowedFields = [
        'game_Id',
        'cust_Id',
        // 'player_date',
        'player_score',
        'player_rank',
        'player_winning_status',
        'player_status',
        'player_created_at',
        'player_created_by',
        'player_updated_at',
        'player_updated_by'
    ];

    public function getUserPlayedGames($userId, $postData)
    {
        // Remove all spaces & tabs from search input
        $searchValue = trim(preg_replace('/\s+/', '', $postData['search']['value'] ?? ''));

        $builder = $this->db->table('players p');

        //     $builder->select("
        //     p.player_Id,
        //     p.player_created_at,
        //     p.player_rank,
        //     g.game_name,
        //     sb.score,
        //     sb.score_created_at
        // ");
        $builder->select("
    p.player_Id,
    p.player_created_at,
    p.player_rank,
    g.game_name,
    sb.score,
    sb.score_created_at,
    (
        SELECT MAX(sb2.score)
        FROM score_board sb2
        WHERE sb2.player_Id = p.player_Id
    ) AS highest_score
");


        // 🔥 JOIN score_board (multiple scores per player)
        $builder->join('score_board sb', 'sb.player_Id = p.player_Id', 'left');

        // JOIN game table
        $builder->join('game g', 'g.game_Id = sb.game_Id', 'left');

        $builder->where('p.cust_Id', $userId);

        // Order by latest score entry
        $builder->orderBy('sb.score_created_at', 'DESC');

        // SEARCH
        if (!empty($searchValue)) {
            $escaped = $this->db->escapeLikeString($searchValue);

            $builder->groupStart();
            $builder->where("REPLACE(REPLACE(g.game_name,' ',''), '\t','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(REPLACE(sb.score,' ',''), '\t','') LIKE '%{$escaped}%'", null, false);
            $builder->groupEnd();
        }

        // PAGINATION
        $length = $postData['length'] ?? 10;
        $start = $postData['start'] ?? 0;

        if ($length != -1) {
            $builder->limit($length, $start);
        }

        $result = $builder->get()->getResultArray();

        // Format dates
        // foreach ($result as &$row) {

        //     $row['score_created_at'] = !empty($row['score_created_at'])
        //         ? date("d-m-Y H:i", strtotime($row['score_created_at']))
        //         : "N/A";
        // }

        foreach ($result as &$row) {

            // Only show rank for the highest score
            if ($row['score'] != $row['highest_score']) {
                $row['player_rank'] = "-";
            }

            $row['score_created_at'] = !empty($row['score_created_at'])
                ? date("d-m-Y H:i", strtotime($row['score_created_at']))
                : "N/A";
        }


        return $result;
    }

    public function countAllUserRows($userId)
    {
        return $this->db->table('players')
            ->where('cust_Id', $userId)
            ->countAllResults();
    }

    public function countFilteredRows($userId, $postData)
    {
        $searchValue = trim(preg_replace('/\s+/', '', $postData['search']['value'] ?? ''));

        $builder = $this->db->table('players p')
            ->join('score_board sb', 'sb.player_Id = p.player_Id', 'left')
            ->join('game g', 'g.game_Id = sb.game_Id', 'left');

        $builder->where('p.cust_Id', $userId);

        if (!empty($searchValue)) {
            $escaped = $this->db->escapeLikeString($searchValue);

            $builder->groupStart();
            $builder->where("REPLACE(REPLACE(g.game_name,' ',''), '\t','') LIKE '%{$escaped}%'", null, false);
            $builder->orWhere("REPLACE(REPLACE(sb.score,' ',''), '\t','') LIKE '%{$escaped}%'", null, false);
            $builder->groupEnd();
        }

        return $builder->countAllResults();
    }




}