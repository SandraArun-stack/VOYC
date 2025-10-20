<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class SettingsModel extends Model
{

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    protected $table = 'category';
    protected $primaryKey = 'cat_Id';
    protected $allowedFields = ['cat_Name', 'cat_Discount_Value', 'cat_Discount_Type', 'cat_Status']; // Adjust to your table


    



}



?>