<?php
namespace App\Models;
use CodeIgniter\Model;

class PrdDetailModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'pr_Id';
    protected $allowedFields = ['pr_Name', 'pr_Selling_Price', 'prd_first_image', 'description', 'pr_Sleeve_Style', 'pr_Fabric','pr_Stitch_Type', 'pr_Status', 'cat_Id'];

    public function get_prd_Details($id)
    {
        $product = $this->where('pr_Id', $id)
            ->where('pr_Status', 1)
            ->first();

        if (!$product) {
            return null;
        }

        $builder = $this->db->table('product_image pi')
            ->select('pi.pri_Id, pi.pri_Thumbnail, pi.pri_File_Name')
            ->where('pi.pr_Id', $id)
            ->where('pi.pri_Status', 1)
            ->orderBy('pi.pri_createdon', 'DESC');

        $images = $builder->get()->getResultArray();
        $product['images'] = $images;
        $sizePriority = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
        $selectedPrice = null;
        $selectedSize = null;
        $variants = $this->db->table('product_variants pv')
            ->select('pv.prv_Size, pv.prv_price')
            ->where('pv.pr_Id', $id)
            ->where('pv.prv_Status', 1)
            ->get()
            ->getResultArray();
        foreach ($sizePriority as $size) {
            foreach ($variants as $variant) {
                if (strtoupper(trim($variant['prv_Size'])) === $size) {
                    $selectedPrice = $variant['prv_price'];
                    $selectedSize = $variant['prv_Size'];
                    break 2;
                }
            }
        }
        if ($selectedPrice === null && !empty($variants)) {
            $selectedPrice = $variants[0]['prv_price'];
            $selectedSize = $variants[0]['prv_Size'];
        }

        $product['selected_price'] = $selectedPrice;
        $product['selected_size'] = $selectedSize;
        $product['price_with_size'] = $variants;

        return $product;
    }
}
