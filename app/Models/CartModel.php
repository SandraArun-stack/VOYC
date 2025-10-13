<?php
namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'my_cart';
    protected $primaryKey = 'cart_Id ';
    protected $allowedFields = [
        'cart_Id',
        'cust_Id',
        'pr_Id',
        'pri_Id',
        'cart_Status',
        'design_Id',
        'prv_Id'
    ];

    public function getCartItems($custId)
    {
        $builder = $this->db->table('my_cart c');

        $builder->select('c.*');
        $builder->select('d.design_image');
        $builder->select('p.pr_Name');
        $builder->select("CASE WHEN c.design_Id IS NULL THEN pi.pri_Thumbnail ELSE NULL END AS pri_Thumbnail", false);

        $builder->join('design d', 'c.design_Id = d.design_Id', 'left');
        $builder->join('product_image pi', 'c.pri_Id = pi.pri_Id AND c.design_Id IS NULL', 'left');
        $builder->join('product p', 'c.pr_Id = p.pr_Id', 'left');

        $builder->where('c.cust_Id', $custId);
        $builder->where('c.cart_Status', 1);

        $query = $builder->get();
        return $query->getResultArray();
    }
}
