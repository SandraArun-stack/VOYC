<?php
namespace App\Models;

use CodeIgniter\Model;

class MyOrdersModel extends Model
{
    protected $table = 'order_detail';
    protected $primaryKey = 'od_Id';
    protected $allowedFields = [
        'od_Id',
        'pr_Id',
        'pri_Id',
        'od_Quantity',
        'od_Size',
        'design_Id',
        'od_Color',
        'od_Original_Price',
        'od_Selling_Price',
        'od_Status',
        'od_createdon',
        'od_modifyon',
        'cus_Id',
        'add_Id',
        'od_Grand_Total',
        'pr_Code',
        'od_Shipping_Address'
    ];


    // public function getMyOrders($userId, $perPage = 4)
    // {
    //     $my_orders = $this->select("
    //             order_detail.*,
    //             r.rating AS review_rating, 
    //             r.review,
    //             r.created_at, 
    //             p.pr_Name,
    //             CASE 
    //                 WHEN order_detail.design_Id = 0 OR order_detail.design_Id IS NULL 
    //             THEN (SELECT pi.pri_Thumbnail 
    //                   FROM product_image AS pi 
    //                   WHERE pi.pri_Id = order_detail.pri_Id 
    //                   LIMIT 1)
    //         ELSE (SELECT d.front_Image 
    //               FROM design AS d 
    //               WHERE d.design_Id = order_detail.design_Id
    //               LIMIT 1)
    //     END AS order_Image
    //         ")
    //         ->join("reviews AS r", "r.od_Id = order_detail.od_Id", "left")
    //         ->join("product AS p", "p.pr_Id = order_detail.pr_Id", "left")
    //         ->where('order_detail.cus_Id', $userId)
    //         ->where('order_detail.od_Status !=', 9)
    //         ->orderBy('order_detail.od_createdon', 'DESC')
    //         ->paginate($perPage);

    //     return $my_orders;
    // }


    public function getMyOrders($userId, $perPage = 4, $search = null)
    {
        $builder = $this->select("
        order_detail.*,
        r.rating AS review_rating, 
        r.review,
        r.created_at, 
        p.pr_Name,
        CASE 
            WHEN order_detail.design_Id = 0 OR order_detail.design_Id IS NULL 
                THEN (SELECT pi.pri_Thumbnail FROM product_image AS pi WHERE pi.pri_Id = order_detail.pri_Id LIMIT 1)
            ELSE (SELECT d.front_Image FROM design AS d WHERE d.design_Id = order_detail.design_Id LIMIT 1)
        END AS order_Image
    ")
            ->join("reviews AS r", "r.od_Id = order_detail.od_Id", "left")
            ->join("product AS p", "p.pr_Id = order_detail.pr_Id", "left")
            ->where('order_detail.cus_Id', $userId)
            ->where('order_detail.od_Status !=', 9);

        if ($search) {
            $builder->groupStart()
                ->like('order_detail.od_number', $search)
                ->orLike('p.pr_Name', $search)
                ->orLike('p.pr_Code', $search)
                ->groupEnd();
        }

        return $builder->orderBy('order_detail.od_createdon', 'DESC')
            ->paginate($perPage);
    }

    public function insertRating($data)
    {
        $builder = $this->db->table('reviews');

        $existing = $builder->where('od_Id', $data['od_Id'])->get()->getRow();

        if ($existing) {
            if ($data['rating'] >= $existing->rating) {
                return $builder
                    ->where('od_Id', $data['od_Id'])
                    ->update([
                        'rating' => $data['rating'],
                        'review' => $data['review'],
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
            } else {
                return false;
            }
        } else {
            return $builder->insert($data);
        }
    }



}