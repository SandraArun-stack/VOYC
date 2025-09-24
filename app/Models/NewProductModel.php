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
        return $this->db->table('product_image pi')
            ->select('p.pr_Id, p.pr_Name, p.pr_Selling_Price, pi.pri_Thumbnail AS prd_first_image')
            ->join('product p', 'p.pr_Id = pi.pr_Id')
            ->where('pi.pri_Status', 1)
            ->orderBy('pi.pri_createdon', 'DESC')
            ->limit(8)
            ->get()
            ->getResultArray();
    }

    public function getBestSeller()
    {
        $bestSellers = $this->db->table('order_detail od')
            ->select('p.pr_Id, p.pr_Name, p.pr_Selling_Price, MAX(pi.pri_Thumbnail) AS prd_first_image, COUNT(od.pr_Id) AS frequency')
            ->join('product p', 'p.pr_Id = od.pr_Id')
            ->join('product_image pi', 'pi.pr_Id = p.pr_Id', 'left')
            ->where('p.pr_Status', 1)
            ->where('pi.pri_Status', 1)
            ->groupBy('p.pr_Id, p.pr_Name, p.pr_Selling_Price')
            ->orderBy('frequency', 'DESC')
            ->limit(9)
            ->get()
            ->getResultArray();

        $count = count($bestSellers);

        if ($count < 9) {
            $remaining = 9 - $count;

            $latestProducts = $this->db->table('product p')
                ->select('p.pr_Id, p.pr_Name, p.pr_Selling_Price, pi.pri_Thumbnail AS prd_first_image, 0 AS frequency')
                ->join('product_image pi', 'pi.pr_Id = p.pr_Id', 'left')
                ->where('p.pr_Status', 1)
                ->where('pi.pri_Status', 1)
                ->orderBy('pi.pri_createdon', 'DESC')
                ->limit($remaining)
                ->get()
                ->getResultArray();
            $bestSellers = array_merge($bestSellers, $latestProducts);
        }

        return $bestSellers;
    }




}

