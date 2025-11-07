<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductDetailModel extends Model
{
    protected $table = 'product_image';
    protected $primaryKey = 'pri_Id';
    protected $allowedFields = [
        'pr_Id',
        'pri_Thumbnail',
        'pri_File_Name',
        'pri_Sleev_Name',
        'RSleeve_Img',
        'LSleeve_Img',
        'pri_Video',
        'pri_Status',
        'pri_createdon',
        'pri_createdby',
        'pri_modifyby',
        'pri_modifyon',
        'color_details',
        'stock',
        'reset_stock'
    ];
    public function get_prd_Details($prId, $priId)
    {
        $product = $this->db->table('product p')
            ->select('p.pr_Id, p.pr_Name, p.pr_Selling_Price, p.pr_Description,p.pr_custom')
            ->where('p.pr_Id', $prId)
            ->where('p.pr_Status', 1)
            ->get()
            ->getRowArray();

        if (!$product) {
            return null;
        }

        $images = $this->db->table('product_image pi')
            ->select('pi.pri_Id, pi.pri_Thumbnail, pi.pri_File_Name, pi.pri_Sleev_Name, pi.RSleeve_Img, pi.LSleeve_Img, pi.pri_Video, pi.color_details')
            ->where('pi.pr_Id', $prId)
            ->where('pi.pri_Id', $priId)
            ->where('pi.pri_Status', 1)
            ->orderBy('pi.pri_createdon', 'DESC')
            ->get()
            ->getResultArray();

        $allImages = [];
        foreach ($images as $img) {
            if (!empty($img['pri_Thumbnail'])) {
                $allImages[] = strtolower(trim($img['pri_Thumbnail']));
            }

            foreach (['pri_File_Name', 'pri_Sleev_Name'] as $jsonField) {
                if (!empty($img[$jsonField])) {
                    $fileNames = json_decode($img[$jsonField], true);
                    if (is_array($fileNames)) {
                        foreach ($fileNames as $file) {
                            $allImages[] = strtolower(trim($file));
                        }
                    }
                }
            }
        }

        $product['images'] = array_values(array_unique($allImages));

        // ✅ Color options
        $colorDetails = $this->db->table('product_image pi')
            ->select('pi.color_details, pi.pri_Id, pi.pri_Thumbnail')
            ->where('pi.pr_Id', $prId)
            ->where('pi.pri_Status', 1)
            ->get()
            ->getResultArray();

        $colors = [];
        foreach ($colorDetails as $clr) {
            if (!empty($clr['color_details'])) {
                $colorData = json_decode($clr['color_details'], true);
                if (!empty($colorData['color'])) {
                    $colors[] = [
                        'pri_Id' => $clr['pri_Id'],
                        'color' => strtolower(trim($colorData['color'])),
                        'thumbnail' => strtolower(trim($clr['pri_Thumbnail']))
                    ];
                }
            }
        }

        $product['colors'] = $colors;

        // ✅ Get all variants (include ID for cart linkage)
        $variants = $this->db->table('product_variants pv')
            ->select('pv.prv_Id, pv.prv_Size, pv.prv_price, pv.prv_Color, pv.prv_Fabric, pv.stock, pv.reset_stock')
            ->where('pv.pr_Id', $prId)
            ->where('pv.prv_Status', 1)
            ->get()
            ->getResultArray();

        // Sort sizes in logical order
        $customSizeOrder = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

        usort($variants, function ($a, $b) use ($customSizeOrder) {
            return array_search($a['prv_Size'], $customSizeOrder) - array_search($b['prv_Size'], $customSizeOrder);
        });

        $product['sizes'] = $variants; // ✅ Each item now has 'prv_Id', 'prv_Size', 'prv_price'

        // ✅ Reviews and ratings
        $reviews = $this->db->table('reviews r')
            ->select('r.name, r.rating, r.review, r.created_at')
            ->where('r.pr_Id', $prId)
            ->where('r.pr_Status', 1)
            ->orderBy('r.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $totalRating = array_sum(array_column($reviews, 'rating'));
        $averageRating = count($reviews) > 0 ? round($totalRating / count($reviews), 1) : 0;

        $product['average_rating'] = $averageRating;
        $product['reviews'] = $reviews;
        $product['review_count'] = count($reviews);

        $session = session();
        $custId = $session->get('user_id');

        $product['in_cart'] = false;

        if (!empty($custId)) {
            $exists = $this->db->table('my_cart')
                ->where('cust_Id', $custId)
                ->where('pr_Id', $prId)
                ->where('pri_Id', $priId)
                ->where('cart_Status', 1)
                ->countAllResults();

            if ($exists > 0) {
                $product['in_cart'] = true;
            }
        }


        return $product;
    }

    public function getImageByColor($priId)
    {
        return $this->db->table('product_image')
            ->select('pri_Thumbnail , pri_File_Name')
            ->where('pri_Id', $priId)
            ->where('pri_Status', 1)
            ->get()
            ->getRowArray();
    }

    public function getSizesByColor($priId)
    {
        $sizes = $this->db->table('product_variants')
            ->select('prv_Id, prv_Size, prv_price')
            ->where('pri_id', $priId)
            ->where('prv_Status', 1)
            ->get()
            ->getResultArray();

        // ✅ Define custom order for logical size sorting
        $customOrder = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

        usort($sizes, function ($a, $b) use ($customOrder) {
            return array_search($a['prv_Size'], $customOrder) - array_search($b['prv_Size'], $customOrder);
        });

        return $sizes;
    }


    public function saveToCart($data)
    {
        $builder = $this->db->table('my_cart');

        // Check if item with same product, color, and size already exists in user's cart
        $exists = $builder->where([
            'cust_Id' => $data['cust_Id'],
            'pr_Id' => $data['pr_Id'],
            'pri_Id' => $data['pri_Id'],
            'prv_Id' => $data['prv_Id'],
            'cart_Status' => 1
        ])->get()->getRowArray();

        if ($exists) {
            // If already exists → increase quantity
            $builder->where('cart_Id', $exists['cart_Id'])
                ->set('cart_Quantity', $exists['cart_Quantity'] + $data['cart_Quantity'])
                ->update();
            return 'updated';
        } else {
            // If not exists → insert new record
            $builder->insert([
                'cust_Id' => $data['cust_Id'],
                'pr_Id' => $data['pr_Id'],
                'pri_Id' => $data['pri_Id'],
                'prv_Id' => $data['prv_Id'],
                'design_Id' => $data['design_Id'] ?? 0,
                'cart_Quantity' => $data['cart_Quantity'] ?? 1,
                'cart_Price' => $data['price'] ?? 0,
                'cart_Status' => 1
            ]);
            return 'inserted';
        }
    }




}

