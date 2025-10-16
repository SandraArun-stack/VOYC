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
    public function get_Data_For_Pr_Id($prId)
    {
        // Get all image/color data
        $images = $this->db->table('product_image')
            ->select('pri_Id, pr_Id, pri_Thumbnail, pri_File_Name, pri_Sleev_Name, color_details')
            ->where('pr_Id', $prId)
            ->get()
            ->getResultArray();

        // Decode JSON fields safely
        foreach ($images as &$img) {
            if (isset($img['pri_File_Name'])) {
                $img['pri_File_Name'] = json_decode($img['pri_File_Name'], true);
            }
            if (isset($img['pri_Sleev_Name'])) {
                $img['pri_Sleev_Name'] = json_decode($img['pri_Sleev_Name'], true);
            }
        }

        // Get all variant (size, price) data
        $variants = $this->db->table('product_variants')
            ->select('prv_Id, pr_Id, pri_id, prv_Size, prv_price, stock')
            ->where('pr_Id', $prId)
            ->get()
            ->getResultArray();

        // Merge variants into images by pri_Id
        foreach ($images as &$img) {
            $img['variants'] = array_values(array_filter($variants, function ($v) use ($img) {
                return $v['pri_id'] == $img['pri_Id'];
            }));
        }

        return $images;
    }

}