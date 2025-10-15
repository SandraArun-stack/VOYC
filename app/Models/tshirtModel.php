<?php
namespace App\Models;
use CodeIgniter\Model;

class tshirtModel extends Model
{
    protected $table = 'design';
    protected $primaryKey = 'design_Id';

    protected $allowedFields = [
        'design_Id ',
        'cust_Id',
        'pr_Id',
        'pri_Id',
        'front_Image',
        'back_Image',
        'sleeve_Image',
        'created_on'
    ];

    public function insertDesign($data)
    {
        $this->db->table($this->table)->insert($data);
        return $this->db->insertID();
    }
    public function get_Image($prId, $priId)
    {
        $image = $this->db->table('product_image')
            ->select('pri_Thumbnail, pri_File_Name,pri_Sleev_Name,pri_Id ,pr_Id,stock,reset_stock')
            ->where('pr_Id', $prId)
            ->where('pri_Id', $priId)
            ->get()
            ->getRowArray();
        if ($image && isset($image['pri_File_Name'])) {
            $image['pri_File_Name'] = json_decode($image['pri_File_Name']); // If it's a JSON string, decode it
        }
        if ($image && isset($image['pri_Sleev_Name'])) {
            $image['pri_Sleev_Name'] = json_decode($image['pri_Sleev_Name']); // If it's a JSON string, decode it
        }

        return $image;
    }
}