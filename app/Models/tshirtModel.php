<?php
namespace App\Models;
use CodeIgniter\Model;

class tshirtModel extends Model
{
    protected $table = 'design';
    protected $primaryKey = 'design_Id';

    protected $allowedFields = [
        'design_Image',
        'created_on'
    ];

    public function insertDesign($data)
    {
        $this->db->table($this->table)->insert($data);
        return $this->db->insertID(); 
    }
}