<?php
namespace App\Models;
use CodeIgniter\Model;

class PrdDetailModel extends Model
{
    protected $table = 'products'; // main product table
    protected $primaryKey = 'pr_Id';
    protected $allowedFields = ['pr_Name', 'pr_Selling_Price', 'prd_first_image', 'description', 'pr_Status'];

    public function get_prd_Details($id)
    {
        // fetch main product
        $product = $this->where('pr_Id', $id)
            ->where('pr_Status', 1)
            ->first();

        if (!$product) {
            return null;
        }

        // fetch related images
        $builder = $this->db->table('product_image pi')
            ->select('pi.pri_Id, pi.pri_Thumbnail, pi.pri_File_Name')
            ->where('pi.pr_Id', $id)
            ->where('pi.pri_Status', 1)
            ->orderBy('pi.pri_createdon', 'DESC');

        $images = $builder->get()->getResultArray();

        // attach images
        $product['images'] = $images;

        return $product;
    }
}
