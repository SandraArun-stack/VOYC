<?php
namespace App\Models;

use CodeIgniter\Model;

class AllCustProductModel extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'pr_Id';
    protected $allowedFields = [
        'pr_Id',
        'pr_Name',
        'pr_Code',
        'pr_Description',
        'pr_Status',
        'pr_custom'
    ];
    public function getAllCustomProducts()
    {
        $result = $this->select('product.*, product_image.pri_Thumbnail, product_image.pri_Status, product_image.pri_Id')
            ->join('product_image', 'product_image.pr_Id = product.pr_Id', 'left')
            ->where('product.pr_custom', 1)
            ->where('product.pr_Status', 1)
            ->where('product_image.pri_Status', 1)
            ->groupBy('product.pr_Id')
            ->findAll();

        $sizePriority = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

        foreach ($result as &$prd) {
            $reviews = $this->db->table('reviews r')
                ->select('r.rating')
                ->where('r.pr_Id', $prd['pr_Id'])
                ->where('r.pr_Status', 1)
                ->get()
                ->getResultArray();

            $totalRating = 0;
            foreach ($reviews as $rev) {
                $totalRating += $rev['rating'];
            }

            $averageRating = count($reviews) > 0 ? round($totalRating / count($reviews), 1) : 0;

            $prd['average_rating'] = $averageRating;

            $variants = $this->db->table('product_variants pv')
                ->select('pv.prv_Size, pv.prv_price')
                ->where('pv.pr_Id', $prd['pr_Id'])
                ->where('pv.prv_Status', 1)
                ->get()
                ->getResultArray();

            $selectedPrice = null;
            $selectedSize = null;

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

            $prd['selected_price'] = $selectedPrice;
            $prd['selected_size'] = $selectedSize;
            $prd['price_with_size'] = $variants;
        }
        return $result;
    }

}
