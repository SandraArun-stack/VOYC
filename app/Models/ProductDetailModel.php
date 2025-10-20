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
            ->select('p.pr_Id, p.pr_Name, p.pr_Selling_Price, p.pr_Description')
            ->where('p.pr_Id', $prId)
            ->where('p.pr_Status', 1)
            ->get()
            ->getRowArray();

        if (!$product) {
            return null;
        }

        $images = $this->db->table('product_image pi')
            ->select('pi.pri_Id, pi.pri_Thumbnail, pi.pri_File_Name,pi.pri_Sleev_Name, pi.color_details')
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

            if (!empty($img['pri_File_Name'])) {
                $fileNames = json_decode($img['pri_File_Name'], true);
                if (is_array($fileNames)) {
                    foreach ($fileNames as $file) {
                        $allImages[] = strtolower(trim($file));
                    }
                }
            }
            if (!empty($img['pri_Sleev_Name'])) {
                $fileNames = json_decode($img['pri_Sleev_Name'], true);
                if (is_array($fileNames)) {
                    foreach ($fileNames as $file) {
                        $allImages[] = strtolower(trim($file));
                    }
                }
            }
        }

        $allImages = array_values(array_unique($allImages));

        $product['images'] = $allImages;

        $colorDetails = $this->db->table('product_image pi')
            ->select('pi.color_details , pi.pri_Id, pi.pri_Thumbnail')
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

        $variants = $this->db->table('product_variants pv')
            ->select('pv.prv_Size,pv.prv_price, pv.prv_Color, pv.prv_Fabric, pv.stock, pv.reset_stock')
            ->where('pv.pr_Id', $prId)
            ->where('pv.prv_Status', 1)
            ->get()
            ->getResultArray();

        $customSizeOrder = ['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

        $sizes = array_map(function ($v) {
            return $v['prv_Size'];
        }, $variants);
        $sizes = array_values(array_unique($sizes));

        usort($sizes, function ($a, $b) use ($customSizeOrder) {
            return array_search($a, $customSizeOrder) - array_search($b, $customSizeOrder);
        });

        $product['sizes'] = $sizes;

        $reviews = $this->db->table('reviews r')
            ->select('r.name,r.rating, r.review, r.created_at')
            ->where('r.pr_Id', $prId)
            ->where('r.pr_Status', 1)
            ->orderBy('r.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $totalRating = 0;

        foreach ($reviews as $rev) {
            $totalRating += $rev['rating'];
        }

        $averageRating = count($reviews) > 0 ? round($totalRating / count($reviews), 1) : 0;

        $product['average_rating'] = $averageRating;
        $product['reviews'] = $reviews;
        $product['review_count'] = count($reviews);

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


}

