<?php
namespace App\Models;
use CodeIgniter\Model;
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

// public function searchProducts($keyword)
// {
//     $keyword = trim($keyword);
//     if ($keyword === '') return [];

//     $builder = $this->db->table('product p');
//     $builder->select('p.*, pi.pri_Id, pi.pri_Thumbnail, c.cat_Name, s.sub_Category_Name');
//     $builder->join('product_image pi', 'pi.pr_Id = p.pr_Id', 'left');
//     $builder->join('category c', 'c.cat_Id = p.cat_Id', 'left');
//     $builder->join('subcategory s', 's.sub_Id = p.sub_Id', 'left');

//     $builder->groupStart()
//         ->like('LOWER(p.pr_Name)', strtolower($keyword))
//         ->orLike('LOWER(p.pr_Description)', strtolower($keyword))
//         ->orLike('LOWER(p.pr_Fabric)', strtolower($keyword))
//         ->orLike('LOWER(p.pr_Sleeve_Style)', strtolower($keyword))
//         ->orLike('LOWER(p.pr_Stitch_Type)', strtolower($keyword))
//         ->orLike('LOWER(c.cat_Name)', strtolower($keyword))
//         ->orLike('LOWER(s.sub_Category_Name)', strtolower($keyword))
//         ->orWhere("SOUNDEX(p.pr_Name) = SOUNDEX(" . $this->db->escape($keyword) . ")", null, false)
//         ->orWhere("SOUNDEX(s.sub_Category_Name) = SOUNDEX(" . $this->db->escape($keyword) . ")", null, false)
//     ->groupEnd();

//     $builder->where('p.pr_Status', 1);
//     $builder->where('pi.pri_Status', 1);

//     $results = $builder->get()->getResultArray();

//     // ✅ Add price and size info before returning
//     return $this->attachProductDetails($results);
// }

public function searchProducts($keyword)
{
    $keyword = trim($keyword);
    if ($keyword === '') return [];

    // Normalize keyword
    $rawKeyword = $keyword;
    $lowerKeyword = mb_strtolower($rawKeyword, 'UTF-8');

    // If user typed a price range like "100-500"
    $minPrice = null;
    $maxPrice = null;
    if (preg_match('/^\s*(\d+(?:\.\d+)?)\s*-\s*(\d+(?:\.\d+)?)\s*$/', $keyword, $m)) {
        $minPrice = (float)$m[1];
        $maxPrice = (float)$m[2];
    } elseif (preg_match('/^\s*(\d+(?:\.\d+)?)\s*$/', $keyword, $mSingle)) {
        // Single number: we'll treat it as price search (exact or near)
        $minPrice = (float)$mSingle[1] * 0.9; // -10%
        $maxPrice = (float)$mSingle[1] * 1.1; // +10%
    }

    // normalized no-space version for matching "tshirt" -> "t-shirt" etc.
    $normalized = preg_replace('/[^a-z0-9]/', '', $lowerKeyword);

    $builder = $this->db->table('product p');
    $builder->select('p.*, pi.pri_Id, pi.pri_Thumbnail, c.cat_Name, s.sub_Category_Name');
    $builder->join('product_image pi', 'pi.pr_Id = p.pr_Id', 'left');
    $builder->join('category c', 'c.cat_Id = p.cat_Id', 'left');
    $builder->join('subcategory s', 's.sub_Id = p.sub_Id', 'left');
    // join variants so we can search size & price
    $builder->join('product_variants pv', 'pv.pr_Id = p.pr_Id', 'left');

    // Start grouped WHERE
    $builder->groupStart();

    // 1) Direct LIKE on common text columns
    $builder->like('LOWER(p.pr_Name)', $lowerKeyword);
    $builder->orLike('LOWER(p.pr_Description)', $lowerKeyword);
    $builder->orLike('LOWER(p.pr_Fabric)', $lowerKeyword);
    $builder->orLike('LOWER(p.pr_Sleeve_Style)', $lowerKeyword);
    $builder->orLike('LOWER(p.pr_Stitch_Type)', $lowerKeyword);
    $builder->orLike('LOWER(c.cat_Name)', $lowerKeyword);
    $builder->orLike('LOWER(s.sub_Category_Name)', $lowerKeyword);

    // 2) Size match (search in variants)
    // Normalize size input: if someone types "xl" or "x l" etc., normalize spaces
    $sizeCandidate = preg_replace('/\s+/', '', strtoupper($rawKeyword));
    // common sizes are short strings; include a check to avoid matching large words as sizes
    if (in_array($sizeCandidate, ['XXS','XS','S','M','L','XL','XXL','XXXL'], true)) {
        $builder->orWhere('UPPER(TRIM(pv.prv_Size)) =', $sizeCandidate);
    } else {
        // Also try matching if keyword contains typical size words (e.g., "size m", "m")
        $builder->orLike('UPPER(pv.prv_Size)', strtoupper($rawKeyword));
    }

    // 3) Price matches (if we parsed a number or range)
    if ($minPrice !== null || $maxPrice !== null) {
        if ($minPrice !== null) {
            $builder->orWhere('pv.prv_price >=', $minPrice);
        }
        if ($maxPrice !== null) {
            $builder->orWhere('pv.prv_price <=', $maxPrice);
        }
    }

    // 4) Normalized name / category matches (removes spaces, hyphens, underscores)
    // Use escapeLikeString to safely embed normalized value in LIKE
    $db = $this->db;
    $escapedNorm = $db->escapeLikeString($normalized);

    // Raw SQL because builder doesn't provide REPLACE(...) helpers
    // Search product name / category / subcategory after removing non-alphanumeric characters
    $builder->orWhere("REPLACE(LOWER(p.pr_Name), ' ', '') LIKE '%" . $escapedNorm . "%'", null, false);
    $builder->orWhere("REPLACE(LOWER(c.cat_Name), ' ', '') LIKE '%" . $escapedNorm . "%'", null, false);
    $builder->orWhere("REPLACE(LOWER(s.sub_Category_Name), ' ', '') LIKE '%" . $escapedNorm . "%'", null, false);

    // 5) SOUNDEX fallback for phonetic matches (helps with typos)
    // Compare SOUNDEX of keyword with product name / subcategory / category
    $builder->orWhere("SOUNDEX(p.pr_Name) = SOUNDEX(" . $db->escape($rawKeyword) . ")", null, false);
    $builder->orWhere("SOUNDEX(s.sub_Category_Name) = SOUNDEX(" . $db->escape($rawKeyword) . ")", null, false);
    $builder->orWhere("SOUNDEX(c.cat_Name) = SOUNDEX(" . $db->escape($rawKeyword) . ")", null, false);

    $builder->groupEnd(); // end grouped OR conditions

    // Only active products / images / variants
    $builder->where('p.pr_Status', 1);
    $builder->where('pi.pri_Status', 1);
    $builder->where('pv.prv_Status', 1);

    // Distinct products (group by product id)
    $builder->groupBy('p.pr_Id');
    $builder->orderBy('p.pr_Id', 'DESC');

    $results = $builder->get()->getResultArray();

    // Attach price/variant/rating info and return
    return $this->attachProductDetails($results);
}


}