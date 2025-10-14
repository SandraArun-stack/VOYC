<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class ProductImageModel extends Model
{
    protected $table = 'product_image';
    protected $primaryKey = 'pri_Id';
    protected $allowedFields = [
        'pr_Id',
        'pri_Thumbnail',
        'pri_File_Name',
        'pri_Sleev_Name',
        'color_details',
        'pri_Status',
        'pri_createdon',
        'pri_createdby'
    ];

    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    // -------------------
    // PRODUCT IMAGE METHODS
    // -------------------

    public function getAllProductImages()
    {
        return $this->db->table($this->table)
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

    public function getProductImages($pri_id = null)
    {
        $builder = $this->db->table($this->table)
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

    public function productimageInsert($data)
    {
        return $this->insert($data);
    }

    public function updateProductimage($id, $data)
    {
        return $this->update($id, $data);
    }

    public function insertProductImages($data)
    {
        $this->insert($data);
        return $this->getInsertID();
    }

    public function deleteProductImage($pri_id)
    {
        return $this->delete($pri_id);
    }

    // -------------------
    // VARIANT METHODS
    // -------------------

    // public function insertVariant($data)
    // {
    //     return $this->db->table('product_variants')->insert($data);
    // }

    public function insertVariant($data)
    {
        // Insert the variant
        $this->db->table('product_variants')->insert($data);

        // Get the inserted ID
        $prv_id = $this->db->insertID();

        // Update the newly inserted variant's status to 1
        $this->db->table('product_variants')
            ->where('prv_id', $prv_id)
            ->update(['prv_Status' => 1]);

        return $prv_id;
    }

    public function getVariantByPriIdSizeAndPrvId($pri_id, $size, $prv_id)
    {
        // Check for variant by pri_id (product image ID), prv_Size (variant size), and prv_id
        return $this->db->table('product_variants')
            ->where('pri_id', $pri_id)   // Match the product image ID
            ->where('prv_Size', $size)   // Match the size of the variant
            ->where('prv_id', $prv_id)   // Match the variant ID
            ->get()
            ->getRowArray();            // Return the first result or null
    }

    public function updateVariant($prv_id, $variantData)
    {
        // Update the variant in the 'product_variants' table
        return $this->db->table('product_variants')
            ->where('prv_id', $prv_id) // Find by prv_id
            ->update($variantData);    // Update with the provided variant data
    }


    public function getVariantsByPriId($pri_id)
    {
        return $this->db->table('product_variants')
            ->where('pri_id', $pri_id)
            ->get()
            ->getResultArray();
    }

    public function getVariantByPriIdAndSize($pri_id, $size)
    {
        return $this->db->table('product_variants')
            ->where('pri_id', $pri_id)
            ->where('prv_Size', $size)
            ->get()
            ->getRowArray();
    }

    // -------------------
    // DATATABLE METHODS
    // -------------------

    public function getDatatables($pr_id = null)
    {
        $builder = $this->db->table('product_image pi')
            ->select('
                pi.pri_id AS pri_Id,
                p.pr_id,
                p.pr_name,
                pi.color_details,
                pi.pri_Status,
                pv.prv_Size,
                pv.prv_price,
                pv.stock,
                pv.reset_stock
            ')
            ->join('product p', 'pi.pr_id = p.pr_id', 'left')
            ->join('product_variants pv', 'pi.pri_id = pv.pri_id', 'left')
            ->where('p.pr_Status !=', 3);

        if ($pr_id !== null) {
            $builder->where('pi.pr_id', $pr_id);
        }

        $postData = service('request')->getPost();

        // Search
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
}
