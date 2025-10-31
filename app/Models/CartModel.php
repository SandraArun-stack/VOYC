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
        'cart_Quantity'
    ];

    


public function getCartItems($custId)
{
    $builder = $this->db->table('my_cart c');

    $builder->select('c.*');
    $builder->select('d.front_Image,d.back_Image,d.RSleeve_Image,d.LSleeve_Image');
    $builder->select('p.pr_Name');
    $builder->select("COALESCE(NULLIF(d.front_Image,''), pi.pri_Thumbnail, 'default.jpg') AS pri_Thumbnail", false);
    $builder->select('pv.prv_price, pv.prv_Size');

    $builder->join('design d', 'c.design_Id = d.design_Id', 'left');
    $builder->join('product_image pi', 'c.pri_Id = pi.pri_Id', 'left');
    $builder->join('product p', 'c.pr_Id = p.pr_Id', 'left');
    $builder->join('product_variants pv', 'c.prv_Id = pv.prv_Id', 'left');

    $builder->where('c.cust_Id', $custId);
    $builder->where('c.cart_Status', 1);
    $builder->orderBy('c.cart_Id', 'DESC');

    $query = $builder->get();
    return $query->getResultArray();
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


}
