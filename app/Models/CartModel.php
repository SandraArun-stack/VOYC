<?php
namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'my_cart';
    protected $primaryKey = 'cart_Id';
    protected $allowedFields = [
        'cart_Id',
        'cust_Id',
        'pr_Id',
        'pri_Id',
        'cart_Status',
        'design_Id',
        'prv_Id',
        'cart_Quantity',
        'cart_Price',
        'cart_Size'
    ];




    // public function getCartItems($custId)
    // {
    //     $builder = $this->db->table('my_cart c');

    //     $builder->select('c.*');
    //     $builder->select('d.front_Image,d.back_Image,d.RSleeve_Image,d.LSleeve_Image');
    //     $builder->select('p.*');
    //     $builder->select("COALESCE(NULLIF(d.front_Image,''), pi.pri_Thumbnail, 'default.jpg') AS pri_Thumbnail", false);
    //     $builder->select('pv.prv_price, pv.prv_Size');

    //     $builder->select("(SELECT JSON_ARRAYAGG(JSON_OBJECT('prv_Id', prv_Id, 'prv_Size', prv_Size, 'prv_price', prv_price))
    //                    FROM product_variants 
    //                    WHERE pri_Id = c.pri_Id) AS size_options", false);

    //     $builder->join('design d', 'c.design_Id = d.design_Id', 'left');
    //     $builder->join('product_image pi', 'c.pri_Id = pi.pri_Id', 'left');
    //     $builder->join('product p', 'c.pr_Id = p.pr_Id', 'left');
    //     $builder->join('product_variants pv', 'c.prv_Id = pv.prv_Id', 'left');

    //     $builder->where('c.cust_Id', $custId);
    //     $builder->where('c.cart_Status', 1);
    //     $builder->orderBy('c.cart_Id', 'DESC');

    //     $query = $builder->get();
    //     return $query->getResultArray();
    // }

    public function getCartItems($custId)
    {
        $builder = $this->db->table('my_cart c');

        $builder->select('c.*');
        $builder->select('d.front_Image,d.back_Image,d.RSleeve_Image,d.LSleeve_Image');
        $builder->select('p.*');

        $builder->select("
            COALESCE(NULLIF(d.front_Image,''), pi.pri_Thumbnail, 'default.jpg') AS pri_Thumbnail
        ", false);

        $builder->select('pv.prv_price, pv.prv_Size');

        $builder->select("IFNULL(ROUND(AVG(r.rating),1), 0) AS average_rating", false);

        $builder->select("
            GROUP_CONCAT(
                CONCAT(pv2.prv_Id, '::', pv2.prv_Size, '::', pv2.prv_price)
            ) AS size_options
        ", false);

        $builder->join('design d', 'c.design_Id = d.design_Id', 'left');
        $builder->join('product_image pi', 'c.pri_Id = pi.pri_Id', 'left');
        $builder->join('product p', 'c.pr_Id = p.pr_Id', 'left');
        $builder->join('product_variants pv', 'c.prv_Id = pv.prv_Id', 'left');
        $builder->join('product_variants pv2', 'pv2.pri_Id = c.pri_Id', 'left');

        $builder->join('reviews r', 'r.pr_Id = p.pr_Id AND r.pr_Status = 1', 'left');
        
        $builder->where('c.cust_Id', $custId);
        $builder->where('c.cart_Status', 1);
        $builder->groupBy('c.cart_Id');
        $builder->orderBy('c.cart_Id', 'DESC');

        $query = $builder->get();
        $result = $query->getResultArray();
        foreach ($result as &$row) {
            $sizes = [];

            if (!empty($row['size_options'])) {
                $items = explode(',', $row['size_options']);

                foreach ($items as $item) {
                    $parts = explode('::', $item);

                    if (count($parts) === 3) {
                        $sizes[] = [
                            'prv_Id' => $parts[0],
                            'prv_Size' => $parts[1],
                            'prv_price' => $parts[2],
                        ];
                    }
                }
            }

            $row['size_options'] = $sizes;
        }

        return $result;
    }
    public function getCartPrice($userId)
    {
        $builder = $this->db->table('my_cart c');

        $builder->select('c.*,pv.*');
        $builder->join('product_variants pv', 'c.prv_Id = pv.prv_Id', 'left');

        $builder->where('c.cust_Id', $userId);
        $builder->where('c.cart_Status', 1); // Assuming 1 means active cart items

        $query = $builder->get();
        $cartItems = $query->getResultArray();

        $totalPrice = 0;

        foreach ($cartItems as $item) {
            $totalPrice += $item['prv_price'] * $item['cart_Quantity'];
        }

        return $totalPrice;
    }


    public function clearCart($userId)
    {
        return $this->where('cust_Id', $userId)->delete();
    }
    public function getCartItemCount($userId)
    {
        return $this->where('cust_Id', $userId)->where('cart_Status', 1)->countAllResults();
    }

    // public function clearCartwhenFreeTee($userId,$cartId)
    // {
    //     return $this->where('cust_Id', $userId)
    //         ->where('cart_Id', $cartId)
    //         ->where('cart_Status', 1)
    //         ->countAllResults();

    // }

}
