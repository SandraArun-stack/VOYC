<?php
namespace App\Models;
use CodeIgniter\Model;

// class ShopModel extends Model
// {
//     protected $table = 'product';
//     protected $primaryKey = 'pr_Id';
//     protected $allowedFields = ['pr_Id', 'pr_Name', 'pr_Code', 'pr_Description', 'cat_Id', 'sub_Id', 'pr_Status', 'pr_for', 'custom', 'pr_custom'];
//     public $timestamps = false;
//     public function displayedItem($category)
//     {
//         $result = $this->db->table('product p')
//             ->select('p.pr_Id, p.pr_Name, p.pr_Selling_Price, p.pr_Description, 
//                   pi.pri_Id, pi.pri_Thumbnail,p.pr_custom')
//             ->join('product_image pi', 'pi.pr_Id = p.pr_Id', 'left')
//             ->where('p.pr_for', $category)
//             ->where('p.pr_Status', 1)
//             ->where('pi.pri_Status', 1)
//             ->where('pi.pri_Id = (SELECT MAX(pi2.pri_Id) FROM product_image pi2 WHERE pi2.pr_Id = p.pr_Id)', null, false)
//             ->orderBy('p.pr_Id', 'DESC')
//             ->get()
//             ->getResultArray();

//         $sizePriority = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

//         foreach ($result as &$prd) {
//             $reviews = $this->db->table('reviews r')
//                 ->select('r.rating')
//                 ->where('r.pr_Id', $prd['pr_Id'])
//                 ->where('r.pr_Status', 1)
//                 ->get()
//                 ->getResultArray();

//             $totalRating = 0;
//             foreach ($reviews as $rev) {
//                 $totalRating += $rev['rating'];
//             }

//             $averageRating = count($reviews) > 0 ? round($totalRating / count($reviews), 1) : 0;

//             $prd['average_rating'] = $averageRating;

//             $variants = $this->db->table('product_variants pv')
//                 ->select('pv.prv_Size, pv.prv_price')
//                 ->where('pv.pr_Id', $prd['pr_Id'])
//                 ->where('pv.prv_Status', 1)
//                 ->get()
//                 ->getResultArray();

//             $selectedPrice = null;
//             $selectedSize = null;

//             foreach ($sizePriority as $size) {
//                 foreach ($variants as $variant) {
//                     if (strtoupper(trim($variant['prv_Size'])) === $size) {
//                         $selectedPrice = $variant['prv_price'];
//                         $selectedSize = $variant['prv_Size'];
//                         break 2;
//                     }
//                 }
//             }

//             if ($selectedPrice === null && !empty($variants)) {
//                 $selectedPrice = $variants[0]['prv_price'];
//                 $selectedSize = $variants[0]['prv_Size'];
//             }

//             $prd['selected_price'] = $selectedPrice;
//             $prd['selected_size'] = $selectedSize;
//             $prd['price_with_size'] = $variants;
//         }

//         return $result;
//     }

//     public function getUniqueCategoriesWithSub($gender)
//     {
//         // Fetch distinct categories based on gender
//         $categories = $this->db->table('product p')
//             ->distinct()
//             ->select('p.cat_Id, c.cat_Name')
//             ->join('product_image pi', 'pi.pr_Id = p.pr_Id', 'left')
//             ->join('category c', 'c.cat_Id = p.cat_Id', 'left')
//             ->where('p.pr_for', $gender)
//             ->where('p.pr_Status', 1)
//             ->where('c.cat_Status', 1)
//             ->where('pi.pri_Status', 1)
//             ->orderBy('c.cat_Name', 'ASC')
//             ->get()
//             ->getResultArray();

//         // Fetch related subcategories for each category
//         foreach ($categories as &$cat) {
//             $subcategories = $this->db->table('subcategory s')
//                 ->distinct()
//                 ->select('s.sub_Id, s.sub_Category_Name')
//                 ->join('product p', 'p.sub_Id = s.sub_Id', 'left')
//                 ->where('s.cat_Id', $cat['cat_Id'])
//                 ->where('s.sub_Status', 1)
//                 ->where('p.pr_Status', 1)
//                 ->orderBy('s.sub_Category_Name', 'ASC')
//                 ->get()
//                 ->getResultArray();

//             $cat['subcategories'] = $subcategories;
//         }

//         return $categories;
//     }

// }

class ShopModel extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'pr_Id';
    protected $allowedFields = [
        'pr_Id',
        'pr_Name',
        'pr_Code',
        'pr_Description',
        'cat_Id',
        'sub_Id',
        'pr_Status',
        'pr_for',
        'custom',
        'pr_custom'
    ];

    protected $sizePriority = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

    public function getDisplayedItems($category)
    {
        $products = $this->db->table('product p')
            ->select('p.pr_Id, p.pr_Name, p.pr_Selling_Price, p.pr_Description, 
                      pi.pri_Id, pi.pri_Thumbnail, p.pr_custom')
            ->join('product_image pi', 'pi.pr_Id = p.pr_Id', 'left')
            ->where('p.pr_for', $category)
            ->where('p.pr_Status', 1)
            ->where('pi.pri_Status', 1)
            ->where('pi.pri_Id = (SELECT MAX(pi2.pri_Id) FROM product_image pi2 WHERE pi2.pr_Id = p.pr_Id)', null, false)
            ->orderBy('p.pr_Id', 'DESC')
            ->get()
            ->getResultArray();

        return $this->attachProductDetails($products);
    }


    public function getProductsBySubcategory($subcategoryIds, $mainCategory, $minPrice = null, $maxPrice = null, $sizes = null, $limit = 9, $offset = 0)
    {

        $countBuilder = $this->db->table('product p')
            ->join('product_variants pv', 'pv.pr_Id = p.pr_Id', 'left')
            ->whereIn('p.sub_Id', (array) $subcategoryIds)
            ->where('p.pr_for', $mainCategory)
            ->where('p.pr_Status', 1)
            ->where('pv.prv_Status', 1);

        if ($minPrice !== null) {
            $countBuilder->where('pv.prv_price >=', $minPrice);
        }
        if ($maxPrice !== null) {
            $countBuilder->where('pv.prv_price <=', $maxPrice);
        }
        // print_r($sizes);exit();

        if (!empty($sizes)) {
            $countBuilder->whereIn('pv.prv_Size', $sizes);
        }

        $countBuilder->groupBy('p.pr_Id');
        $totalProducts = $countBuilder->get()->getNumRows();

        // Fetch paginated filtered products
        $products = $this->db->table('product p')
            ->select('p.*, pi.pri_Id, pi.pri_Thumbnail,pv.prv_price, AVG(r.rating) as average_rating')
            ->join('product_image pi', 'pi.pr_Id = p.pr_Id', 'left')
            ->join('reviews r', 'r.pr_Id = p.pr_Id', 'left')
            ->join('product_variants pv', 'pv.pr_Id = p.pr_Id', 'left')
            ->whereIn('p.sub_Id', (array) $subcategoryIds)
            ->where('p.pr_for', $mainCategory)
            ->where('p.pr_Status', 1)
            ->where('pi.pri_Status', 1)
            ->where('pv.prv_Status', 1);

        if ($minPrice !== null) {
            $products->where('pv.prv_price >=', $minPrice);
        }
        if ($maxPrice !== null) {
            $products->where('pv.prv_price <=', $maxPrice);
        }

        if (!empty($sizes)) {
             $products->whereIn('pv.prv_Size', (array)$sizes);
        }
        $products = $products->groupBy('p.pr_Id')
            ->orderBy('p.pr_Id', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();

        $totalPages = ceil($totalProducts / $limit);

        // Attach variant and rating details
        return [
            'products' => $this->attachProductDetails($products),
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts
        ];
    }


    public function getUniqueCategoriesWithSub($gender)
    {
        $categories = $this->db->table('product p')
            ->distinct()
            ->select('p.cat_Id, c.cat_Name')
            ->join('category c', 'c.cat_Id = p.cat_Id', 'left')
            ->where('p.pr_for', $gender)
            ->where('p.pr_Status', 1)
            ->where('c.cat_Status', 1)
            ->orderBy('c.cat_Name', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($categories as &$cat) {
            $cat['subcategories'] = $this->db->table('subcategory s')
                ->select('s.sub_Id, s.sub_Category_Name')
                ->where('s.cat_Id', $cat['cat_Id'])
                ->where('s.sub_Status', 1)
                ->orderBy('s.sub_Category_Name', 'ASC')
                ->get()
                ->getResultArray();
        }

        return $categories;
    }

    private function attachProductDetails($products)
    {
        foreach ($products as &$prd) {
            // Ratings
            $reviews = $this->db->table('reviews')
                ->select('rating')
                ->where('pr_Id', $prd['pr_Id'])
                ->where('pr_Status', 1)
                ->get()
                ->getResultArray();

            $totalRating = array_sum(array_column($reviews, 'rating'));
            $prd['average_rating'] = count($reviews) ? round($totalRating / count($reviews), 1) : 0;

            // Variants
            $variants = $this->db->table('product_variants')
                ->select('prv_Size, prv_price')
                ->where('pr_Id', $prd['pr_Id'])
                ->where('prv_Status', 1)
                ->get()
                ->getResultArray();

            $selectedPrice = null;
            $selectedSize = null;

            foreach ($this->sizePriority as $size) {
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

        return $products;
    }
    public function getItemIfNoSubProducts($mainCategory)
    {
        $subcategories = $this->db->table('subcategory s')
            ->select('s.sub_Id')
            ->join('product p', 'p.sub_Id = s.sub_Id', 'left')
            ->where('s.sub_Status', 1)
            ->where('p.pr_for', $mainCategory)
            ->get()
            ->getResultArray();

        // Return only the IDs
        return array_column($subcategories, 'sub_Id');
    }
}