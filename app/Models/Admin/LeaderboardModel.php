<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class LeaderboardModel extends Model
{
    protected $table = 'leaderboard';
    protected $primaryKey = 'leaderboard_id';

    protected $allowedFields = [
        'game_id',
        'game_name',
        'date',
        'winners',
        'turns'
    ];

    // Columns for ordering and searching
    protected $column_order = [
        null, 'date', 'game_name', 'winners', 'turns'
    ];
    protected $column_search = [
        'date', 'game_name', 'winners', 'turns'
    ];
    protected $order = ['leaderboard_id' => 'DESC'];

    private function _get_datatables_query()
    {
        $request = service('request');
        $searchValue = $request->getPost('search')['value'] ?? '';

        $builder = $this->db->table($this->table);

        // Searching
        if ($searchValue != '') {
            $builder->groupStart();
            foreach ($this->column_search as $item) {
                $builder->orLike($item, $searchValue);
            }
            $builder->groupEnd();
        }

        // Ordering
        if ($request->getPost('order')) {
            $col = $request->getPost('order')[0]['column'];
            $dir = $request->getPost('order')[0]['dir'];
            $builder->orderBy($this->column_order[$col], $dir);
        } else {
            $builder->orderBy(key($this->order), current($this->order));
        }

        return $builder;
    }

    public function getDatatables()
    {
        $request = service('request');
        $builder = $this->_get_datatables_query();

        if ($request->getPost('length') != -1) {
            $builder->limit($request->getPost('length'), $request->getPost('start'));
        }

        return $builder->get()->getResultArray();
    }

    public function countFiltered()
    {
        $builder = $this->_get_datatables_query();
        return $builder->countAllResults();
    }

    public function countAll()
    {
        return $this->db->table($this->table)->countAllResults();
    }
}
