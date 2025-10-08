<?php
namespace App\Models;
use CodeIgniter\Model;

class ShopModel extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'pr_Id';
    protected $allowedFields = ['pr_Id', 'pr_Name', 'pr_Code', 'pr_Description', 'pr_Status', 'prd_for', 'custom','pr_custom'];
    public $timestamps = false;
    public function displayedItem($category)
    {
        $result = $this->db->table('product p')
            ->select('p.pr_Id, p.pr_Name, p.pr_Selling_Price, p.pr_Description, 
                  pi.pri_Id, pi.pri_Thumbnail,p.pr_custom')
            ->join('product_image pi', 'pi.pr_Id = p.pr_Id', 'left')
            ->where('p.prd_for', $category)
            ->where('p.pr_Status', 1)
            ->where('pi.pri_Status', 1)
            ->where('pi.pri_Id = (SELECT MAX(pi2.pri_Id) FROM product_image pi2 WHERE pi2.pr_Id = p.pr_Id)', null, false)
            ->orderBy('p.pr_Id', 'DESC')
            ->get()
            ->getResultArray();

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

            // Loop through size priority and find the first available size
            foreach ($sizePriority as $size) {
                foreach ($variants as $variant) {
                    if (strtoupper(trim($variant['prv_Size'])) === $size) {
                        $selectedPrice = $variant['prv_price'];
                        $selectedSize = $variant['prv_Size'];
                        break 2; // Exit both loops once found
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

