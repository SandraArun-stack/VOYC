<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class ProductImageModel extends Model
{

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getAllProductImages()
    {
        return $this->db->table('product_image')
            ->select('product_image.*, product.pr_Name, 
                  GROUP_CONCAT(product_variants.prv_Size) as sizes, 
                  GROUP_CONCAT(product_variants.prv_price) as prices,
                  GROUP_CONCAT(product_variants.stock) as stock,
                  GROUP_CONCAT(product_variants.reset_stock) as reset_stock,

                  GROUP_CONCAT(product_variants.prv_Status) as prv_Status')
            ->join('product', 'product.pr_Id = product_image.pr_Id')
            ->join('product_variants', 'product_variants.pri_id = product_image.pri_Id', 'left')
            ->where('product.pr_Status !=', 3)
            ->groupBy('product_image.pri_Id')
            ->get()
            ->getResult();
    }
    //     public function getProductImages($pr_id = null)
// {
//     $builder = $this->db->table('product_image')
//         ->select('product_image.*, product.pr_Name, 
//                   GROUP_CONCAT(product_variants.prv_Size) as sizes, 
//                   GROUP_CONCAT(product_variants.prv_price) as prices,
//                  GROUP_CONCAT(product_variants.stock) as stock,
//                  GROUP_CONCAT(product_variants.reset_stock) as reset_stock,

    //                   MAX(product_variants.prv_Status) as prv_Status')
//         ->join('product', 'product.pr_Id = product_image.pr_Id')
//         ->join('product_variants', 'product_variants.pri_id = product_image.pri_Id', 'left')
//         ->where('product.pr_Status !=', 3);

    //     if ($pr_id !== null) {
//         $builder->where('product_image.pri_Id', $pr_id);
//     }

    //     $builder->groupBy('product_image.pri_Id');

    //     return $builder->get()->getResult();
// }

    public function getProductImages($pri_id = null)
    {
        $builder = $this->db->table('product_image')
            ->select('product_image.*, product.pr_Name, 
                  GROUP_CONCAT(product_variants.prv_Size) as sizes, 
                  GROUP_CONCAT(product_variants.prv_price) as prices,
                  GROUP_CONCAT(product_variants.stock) as stock,
                  GROUP_CONCAT(product_variants.reset_stock) as reset_stock,
                  MAX(product_variants.prv_Status) as prv_Status')
            ->join('product', 'product.pr_Id = product_image.pr_Id')
            ->join('product_variants', 'product_variants.pri_id = product_image.pri_Id', 'left')
            ->where('product.pr_Status !=', 3);

        if ($pri_id !== null) {
            $builder->where('product_image.pri_Id', $pri_id);
        }

        $builder->groupBy('product_image.pri_Id');
        return $builder->get()->getResult();
    }

    public function getAllProducts()
    {
        return $this->db->table('product')->where('pr_Status', 1)->get()->getResult();
    }

    public function productimageInsert($data)
    {
        return $this->db->table('product_image')->insert($data);
    }
    public function updateProductimage($id, $data)
    {
        return $this->db->table('product_image')->where('pri_Id', $id)->update($data);
    }
    public function insertProductImages($data)
    {
        $this->db->table('product_image')->insert($data);
        return $this->db->insertID(); // ✅ return new ID
    }


    //added by spg
    public function insertVariant($data)
    {
        return $this->db->table('product_variants')->insert($data);
    }

    //datatable 
// public function getDatatables()
// {
//     $builder = $this->db->table('product_image pi');
//     $builder->select('
//     pi.pri_id AS pri_Id,
//     p.pr_id,
//     p.pr_name,
//     pi.color_details,
//     pi.pri_Status,
//     pv.prv_Size,
//     pv.prv_price,
//     pv.stock,
//     pv.reset_stock
// ');

    //     $builder->join('product p', 'pi.pr_id = p.pr_id', 'left');
//     $builder->join('product_variants pv', 'pi.pri_id = pv.pri_id', 'left');
//     $builder->where('p.pr_Status !=', 3);

    //     $postData = service('request')->getPost();

    //     // Search
//     if (!empty($postData['search']['value'])) {
//         $search = trim($postData['search']['value']);
//         $escaped = $this->db->escapeLikeString($search);
//         $searchNoSpace = str_replace(' ', '', $search);

    //         $builder->groupStart()
//             // normal fields
//             ->like('p.pr_name', $escaped)
//             ->orLike('pi.color_details', $escaped)
//             ->orLike('pv.prv_Size', $escaped)
//             ->orLike('pv.prv_price', $escaped)
//             ->orLike('pv.stock', $escaped)
//             ->orLike('pv.reset_stock', $escaped)

    //             // space-insensitive search (manual)
//             ->orWhere("REPLACE(p.pr_name, ' ', '') LIKE", "%{$searchNoSpace}%")
//         ->groupEnd();
//     }



    //     // Ordering
//     if (!empty($postData['order'])) {
//         $columns = ['p.pr_name', 'pi.color_details', 'pv.prv_Size', 'pv.prv_price', 'pv.stock', 'pv.reset_stock'];
//         $orderColIndex = $postData['order'][0]['column'];
//         $orderDir = $postData['order'][0]['dir'];
//         $orderCol = $columns[$orderColIndex] ?? 'pi.pri_Id';
//         $builder->orderBy($orderCol, $orderDir);
//     } else {
//         $builder->orderBy('pi.pri_Id', 'DESC');
//     }

    //     // Pagination
//     if (!empty($postData['length']) && $postData['length'] != -1) {
//         $builder->limit($postData['length'], $postData['start']);
//     }

    //     return $builder->get()->getResultArray();
// }


    public function getDatatables($pr_id = null)
    {
        $builder = $this->db->table('product_image pi');
        $builder->select('
        pi.pri_id AS pri_Id,
        p.pr_id,
        p.pr_name,
        pi.color_details,
        pi.pri_Status,
        pv.prv_Size,
        pv.prv_price,
        pv.stock,
        pv.reset_stock
    ');

        $builder->join('product p', 'pi.pr_id = p.pr_id', 'left');
        $builder->join('product_variants pv', 'pi.pri_id = pv.pri_id', 'left');
        $builder->where('p.pr_Status !=', 3);

        // Filter by product if pr_id is passed
        if ($pr_id !== null) {
            $builder->where('pi.pr_id', $pr_id);
        }

        $postData = service('request')->getPost();

        // Search
        if (!empty($postData['search']['value'])) {
            $search = trim($postData['search']['value']);
            $escaped = $this->db->escapeLikeString($search);
            $searchNoSpace = str_replace(' ', '', $search);

            $builder->groupStart()
                ->like('p.pr_name', $escaped)
                ->orLike('pi.color_details', $escaped)
                ->orLike('pv.prv_Size', $escaped)
                ->orLike('pv.prv_price', $escaped)
                ->orLike('pv.stock', $escaped)
                ->orLike('pv.reset_stock', $escaped)
                ->orWhere("REPLACE(p.pr_name, ' ', '') LIKE", "%{$searchNoSpace}%")
                ->groupEnd();
        }

        // Ordering
        if (!empty($postData['order'])) {
            $columns = ['p.pr_name', 'pi.color_details', 'pv.prv_Size', 'pv.prv_price', 'pv.stock', 'pv.reset_stock'];
            $orderColIndex = $postData['order'][0]['column'];
            $orderDir = $postData['order'][0]['dir'];
            $orderCol = $columns[$orderColIndex] ?? 'pi.pri_Id';
            $builder->orderBy($orderCol, $orderDir);
        } else {
            $builder->orderBy('pi.pri_Id', 'DESC');
        }

        // Pagination
        if (!empty($postData['length']) && $postData['length'] != -1) {
            $builder->limit($postData['length'], $postData['start']);
        }

        return $builder->get()->getResultArray();
    }


    // public function countAll()
// {
//     return $this->db->table('product_image pi')
//         ->join('product p', 'pi.pr_id = p.pr_id', 'left')
//         ->join('product_variants pv', 'pi.pri_id = pv.pri_id', 'left')
//         ->where('p.pr_Status !=', 3)
//         ->countAllResults();
// }

    public function countAll($pr_id = null)
    {
        $builder = $this->db->table('product_image pi')
            ->join('product p', 'pi.pr_id = p.pr_id', 'left')
            ->join('product_variants pv', 'pi.pri_id = pv.pri_id', 'left')
            ->where('p.pr_Status !=', 3);

        if ($pr_id !== null) {
            $builder->where('pi.pr_id', $pr_id);
        }

        return $builder->countAllResults();
    }


    // public function countFiltered()
// {
//     $postData = service('request')->getPost();
//     $builder = $this->db->table('product_image pi')
//         ->join('product p', 'pi.pr_id = p.pr_id', 'left')
//         ->join('product_variants pv', 'pi.pri_id = pv.pri_id', 'left')
//         ->where('p.pr_Status !=', 3);

    //     if (!empty($postData['search']['value'])) {
//         $search = trim($postData['search']['value']);
//         $escaped = $this->db->escapeLikeString($search);

    //         $builder->groupStart()
//             ->like('p.pr_name', $escaped)
//             ->orLike('pi.color_details', $escaped)
//             ->orLike('pv.prv_Size', $escaped)
//             ->orLike('pv.prv_price', $escaped)
//             ->orLike('pv.stock', $escaped)
//             ->orLike('pv.reset_stock', $escaped)
//             ->groupEnd();
//     }

    //     return $builder->countAllResults();
// }


    public function countFiltered($pr_id = null)
    {
        $postData = service('request')->getPost();
        $builder = $this->db->table('product_image pi')
            ->join('product p', 'pi.pr_id = p.pr_id', 'left')
            ->join('product_variants pv', 'pi.pri_id = pv.pri_id', 'left')
            ->where('p.pr_Status !=', 3);
        if ($pr_id !== null) {
            $builder->where('pi.pr_id', $pr_id);
        }

        if (!empty($postData['search']['value'])) {
            $search = trim($postData['search']['value']);
            $escaped = $this->db->escapeLikeString($search);

            $builder->groupStart()
                ->like('p.pr_name', $escaped)
                ->orLike('pi.color_details', $escaped)
                ->orLike('pv.prv_Size', $escaped)
                ->orLike('pv.prv_price', $escaped)
                ->orLike('pv.stock', $escaped)
                ->orLike('pv.reset_stock', $escaped)
                ->groupEnd();
        }

        return $builder->countAllResults();
    }


    public function getProductImageById($pri_id)
    {
        $result = $this->db->table('product_image')
            ->where('pri_Id', $pri_id)
            ->get()
            ->getRowArray();
        // echo '<pre>';
        // print_r($result); 
        // echo '</pre>';
        // exit; 
        return $result;

    }

    public function getVariantsByPriId($pri_id)
    {
        $result = $this->db->table('product_variants')
            ->where('pri_id', $pri_id)
            ->get()
            ->getResultArray();

        // echo '<pre>';
        // print_r($result); 
        // echo '</pre>';
        // exit; 

        return $result;
    }


    public function deleteProductImage($pri_id)
    {
        return $this->db->table('product_image')->where('pri_Id', $pri_id)->delete();
    }



}

