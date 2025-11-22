<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class LeaderboardModel extends Model
{
    protected $table = 'leaderboard';
    protected $primaryKey = 'leaderboard_id';

    protected $allowedFields = [
        'date', 'game_id', 'game_name', 'winners', 'turns',
        'created_by', 'created_at', 'updated_by', 'updated_at', 'status'
    ];

    // Function to build query for DataTables with pagination and filtering
    private function _get_datatables_query()
    {
        $builder = $this->builder();
        $builder->where('status !=', 9); // Exclude deleted records

        // Check if there’s a search term
        $searchValue = $_POST['search']['value'] ?? '';
        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('game_name', $searchValue)
                ->orLike('date', $searchValue)
            ->groupEnd();
        }

        return $builder;
    }

    // Function to get the leaderboard data with pagination
    public function getDatatables()
    {
        $builder = $this->_get_datatables_query();

        if ($_POST['length'] != -1) {
            $builder->limit($_POST['length'], $_POST['start']);
        }

        return $builder->get()->getResultArray();
    }

    // Function to count the total number of records after applying filters
    public function countFiltered()
    {
        return $this->_get_datatables_query()->countAllResults(false);
    }

    // Function to count the total number of records
    public function countAll()
    {
        return $this->where('status !=', 9)->countAllResults();
    }
}
