<?php
namespace App\Models;

use CodeIgniter\Model;

class NewProductModel extends Model
{
    protected $table = 'product_image';
    protected $primaryKey = 'pri_Id';
    protected $allowedFields = [
        'pr_Id',
        'pri_Thumbnail',
        'pri_File_Name',
        'pri_Status',
        'pri_createdon',
        'pri_createdby',
        'pri_modifyby',
        'pri_modifyon',
        'color_details',
        'stock',
        'reset_stock'
    ];
    public function getNewPrdImage()
    {
        $newProducts = $this->db->table('product p')
            ->select('p.pr_Id, p.pr_Name, p.pr_Selling_Price, p.pr_Description, 
                  pi.pri_Id, pi.pri_Thumbnail,p.pr_custom,p.pr_for')
            ->join('product_image pi', 'pi.pr_Id = p.pr_Id', 'left')
            ->where('p.pr_Status', 1)
            ->where('pi.pri_Status', 1)
            ->where('pi.pri_Id = (SELECT MAX(pi2.pri_Id) FROM product_image pi2 WHERE pi2.pr_Id = p.pr_Id)', null, false)
            ->orderBy('p.pr_Id', 'DESC')
            ->limit(12)
            ->get()
            ->getResultArray();

        $sizePriority = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

        foreach ($newProducts as &$product) {
            $reviews = $this->db->table('reviews r')
                ->select('r.rating')
                ->where('r.pr_Id', $product['pr_Id'])
                ->where('r.pr_Status', 1)
                ->get()
                ->getResultArray();

            $totalRating = 0;
            foreach ($reviews as $rev) {
                $totalRating += $rev['rating'];
            }

            $averageRating = count($reviews) > 0 ? round($totalRating / count($reviews), 1) : 0;

            $product['average_rating'] = $averageRating;
            $product['review_count'] = count($reviews);
            $product['reviews'] = $reviews;
            $variants = $this->db->table('product_variants pv')
                ->select('pv.prv_Size, pv.prv_price')
                ->where('pv.pr_Id', $product['pr_Id'])
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

            $product['selected_price'] = $selectedPrice;
            $product['selected_size'] = $selectedSize;
            $product['price_with_size'] = $variants;
        }

        return $newProducts;
    }

    public function getBestSeller()
    {
        $bestSellers = $this->db->table('order_detail od')
            ->select('p.pr_Id, p.pr_Name, p.pr_Selling_Price, pi.pri_Id,MAX(pi.pri_Thumbnail) AS prd_first_image, COUNT(od.pr_Id) AS frequency')
            ->join('product p', 'p.pr_Id = od.pr_Id')
            ->join('product_image pi', 'pi.pr_Id = p.pr_Id', 'left')
            ->where('p.pr_Status', 1)
            ->where('pi.pri_Status', 1)
            ->where('pi.pri_Id = (SELECT MAX(pi2.pri_Id) FROM product_image pi2 WHERE pi2.pr_Id = p.pr_Id)', null, false)
            ->groupBy('p.pr_Id, p.pr_Name, p.pr_Selling_Price')
            ->orderBy('frequency', 'DESC')
            ->limit(9)
            ->get()
            ->getResultArray();
        $sizePriority = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

        $count = count($bestSellers);

        if ($count < 9) {
            $remaining = 9 - $count;

            $latestProducts = $this->db->table('product p')
                ->select('p.pr_Id, p.pr_Name, p.pr_Selling_Price,pi.pri_Id, pi.pri_Thumbnail AS prd_first_image, 0 AS frequency')
                ->join('product_image pi', 'pi.pr_Id = p.pr_Id', 'left')
                ->where('p.pr_Status', 1)
                ->where('pi.pri_Status', 1)
                ->where('pi.pri_Id = (SELECT MAX(pi2.pri_Id) FROM product_image pi2 WHERE pi2.pr_Id = p.pr_Id)', null, false)
                ->orderBy('pi.pri_createdon', 'DESC')
                ->limit($remaining)
                ->get()
                ->getResultArray();
            $bestSellers = array_merge($bestSellers, $latestProducts);
        }

        if ($bestSellers) {
            foreach ($bestSellers as &$product) {
                $reviews = $this->db->table('reviews r')
                    ->select('r.rating')
                    ->where('r.pr_Id', $product['pr_Id'])
                    ->where('r.pr_Status', 1)
                    ->get()
                    ->getResultArray();

                $totalRating = 0;
                foreach ($reviews as $rev) {
                    $totalRating += $rev['rating'];
                }

                $averageRating = count($reviews) > 0 ? round($totalRating / count($reviews), 1) : 0;

                $product['average_rating'] = $averageRating;
                $product['review_count'] = count($reviews);
                $product['reviews'] = $reviews;

                $variants = $this->db->table('product_variants pv')
                    ->select('pv.prv_Size, pv.prv_price')
                    ->where('pv.pr_Id', $product['pr_Id'])
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

                $product['selected_price'] = $selectedPrice;
                $product['selected_size'] = $selectedSize;
                $product['price_with_size'] = $variants;
            }
        }
        return $bestSellers;
    }
    public function getPaginatedProducts($perPage = 9)
    {
        return $this->paginate($perPage);
    }
    
   


}

