<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class ProductImageModel extends Model
{

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getAllProductImages()
{
    return $this->db->table('product_image')
        ->select('product_image.*, product.pr_Name, 
                  GROUP_CONCAT(product_variants.prv_Size) as sizes, 
                  GROUP_CONCAT(product_variants.prv_price) as prices,
                  MAX(product_variants.stock) as stock,
                  MAX(product_variants.reset_stock) as reset_stock,
                  MAX(product_variants.prv_Status) as prv_Status')
        ->join('product', 'product.pr_Id = product_image.pr_Id')
        ->join('product_variants', 'product_variants.pri_id = product_image.pri_Id', 'left')
        ->where('product.pr_Status !=', 3)
        ->groupBy('product_image.pri_Id')
        ->get()
        ->getResult();
}
    public function getProductImages($pr_id = null)
{
    $builder = $this->db->table('product_image')
        ->select('product_image.*, product.pr_Name, 
                  GROUP_CONCAT(product_variants.prv_Size) as sizes, 
                  GROUP_CONCAT(product_variants.prv_price) as prices,
                  MAX(product_variants.stock) as stock,
                  MAX(product_variants.reset_stock) as reset_stock,
                  MAX(product_variants.prv_Status) as prv_Status')
        ->join('product', 'product.pr_Id = product_image.pr_Id')
        ->join('product_variants', 'product_variants.pri_id = product_image.pri_Id', 'left')
        ->where('product.pr_Status !=', 3);

    if ($pr_id !== null) {
        $builder->where('product_image.pr_Id', $pr_id);
    }

    $builder->groupBy('product_image.pri_Id');

    return $builder->get()->getResult();
}

    public function getAllProducts()
    {
        return $this->db->table('product')->where('pr_Status', 1)->get()->getResult();
    }

    public function productimageInsert($data)
    {
        return $this->db->table('product_image')->insert($data);
    }
    public function updateProductimage($id, $data)
    {
        return $this->db->table('product_image')->where('pri_Id', $id)->update($data);
    }
   public function insertProductImages($data)
{
    $this->db->table('product_image')->insert($data);
    return $this->db->insertID(); // ✅ return new ID
}


//added by spg
    public function insertVariant($data)
    {
        return $this->db->table('product_variants')->insert($data);
    }

}


?>