<?php
namespace App\Models\Admin;


use CodeIgniter\Model;

class DashboardModel extends Model
{
    protected $db;
    protected $table = 'order_detail';
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    public function getLatestOrderCount()
    {
        $sevenDaysAgo = date('Y-m-d H:i:s', strtotime('-7 days'));

        return $this->db->table($this->table)
            ->where('od_Status', '1')
            ->where('od_createdon >=', $sevenDaysAgo)
            ->countAllResults();
    }

    public function getTotalOrderCount()
    {
        return $this->db->table('order_detail')->countAllResults();
    }

    public function getTotalCustomerCount()
    {
        return $this->db->table('customer')->where('cust_Status', '1')->countAllResults();
    }

    // public function getAnnualRevenue()
    // {
    //     $currentMonth = date('n'); // Numeric representation of month (1-12)
    //     $currentYear = date('Y');

    //     if ($currentMonth >= 4) {
    //         // We're in current financial year starting April 1st this year
    //         $startDate = date('Y-04-01 00:00:00'); // April 1st of current year
    //         $endDate = date(($currentYear + 1) . '-03-31 23:59:59'); // March 31st of next year
    //     } else {
    //         // We're in financial year that started last year
    //         $startDate = date(($currentYear - 1) . '-04-01 00:00:00');
    //         $endDate = date($currentYear . '-03-31 23:59:59');
    //     }

    //     $result = $this->db->table($this->table)
    //         ->selectSum('od_Grand_Total', 'total_revenue')
    //         ->where('od_Status', '4')
    //         ->where('od_createdon >=', $startDate)
    //         ->where('od_createdon <=', $endDate)
    //         ->get()
    //         ->getRow();

    //     return $result->total_revenue ?? 0;
    // }

    public function getAnnualRevenue()
    {
        $currentMonth = date('n');
        $currentYear  = date('Y');

        if ($currentMonth >= 4) {
            $startDate = $currentYear . '-04-01 00:00:00';
            $endDate   = ($currentYear + 1) . '-03-31 23:59:59';
        } else {
            $startDate = ($currentYear - 1) . '-04-01 00:00:00';
            $endDate   = $currentYear . '-03-31 23:59:59';
        }

        $builder = $this->db->table('order_detail');
        $builder->select('IFNULL(SUM(od_Grand_Total), 0) as total_revenue');
        
        // EXCLUDE cancelled records (status != 9)
        $builder->where('od_Status !=', 9);

        $builder->where('od_createdon >=', $startDate);
        $builder->where('od_createdon <=', $endDate);

        $result = $builder->get()->getRow();

        return $result ? $result->total_revenue : 0;
    }



    //  public function getTodaysOrders()
    // {
    //     $todayStart = date('Y-m-d 00:00:00');
    //     $todayEnd   = date('Y-m-d 23:59:59');

    //     return $this->db->table('order_detail AS od')
    //         ->select('od.od_Id, od.od_Grand_Total, od.od_Selling_Price, od.od_DiscountValue, 
    //                 od.od_DiscountType, od.od_Status, c.cust_Name AS customer_name, 
    //                 p.pr_Name AS product_name')
    //         ->join('customer AS c', 'c.cust_Id = od.cus_Id', 'left')
    //         ->join('product AS p', 'p.pr_Id = od.pr_Id', 'left')
    //         ->where('od.od_createdon >=', $todayStart)
    //         ->where('od.od_createdon <=', $todayEnd)
    //         ->orderBy('od.od_createdon', 'DESC')
    //         ->get()
    //         ->getResult();
    // }

    public function getTodaysOrders()
    {
        $today = date('Y-m-d');

        return $this->db->table('order_detail AS od')
            ->select('
                od.od_Number,
                SUM(od.od_Quantity) AS total_quantity,
                MAX(od.od_Grand_Total) AS total_grand,
                MAX(od.od_Status) AS od_Status,
                MAX(od.od_createdon) AS created_on,
                c.cust_Name AS customer_name
            ')
            ->join('customer AS c', 'c.cust_Id = od.cus_Id', 'left')
            ->where('DATE(od.od_createdon)', $today)
            ->groupBy('od.od_Number')     
            ->orderBy('created_on', 'DESC')
            ->get()
            ->getResult();
    }


    // public function getLatestProducts()
    // {
    //     $builder = $this->db->table('product as p');
    //     $builder->select('p.pr_Id, p.pr_Code, p.pr_Name, p.mrp, p.pr_Selling_Price, p.pr_Stock, pi.pri_File_Name');
    //     $builder->join('product_image as pi', 'pi.pr_id = p.pr_Id', 'left');
    //     $builder->where('p.pr_Status', 1);
    //     $builder->orderBy('p.pr_createdon', 'DESC');
    //     $builder->limit(10);
    //     $query = $builder->get();
    //     return $query->getResult();
    // }


    public function getLatestProducts()
{
    $products = $this->db->table('product p')
        ->select('p.pr_Id, p.pr_Code, p.pr_Name, p.pr_Stock, pi.pri_Thumbnail')
        ->join('product_image pi', 'pi.pr_id = p.pr_Id', 'left')
        ->where('p.pr_Status', 1)
        ->orderBy('p.pr_createdon', 'DESC')
        ->limit(10)
        ->get()
        ->getResult();

    $sizes_order = ['S', 'M', 'L', 'XL', 'XXL'];

    foreach ($products as &$product) {
        // Get variants for this product
        $variants = $this->db->table('product_variants')
            ->select('prv_Size, prv_price')
            ->where('pr_Id', $product->pr_Id)
            ->where('prv_Status', 1)
            ->get()
            ->getResult();

        $minPrice = null;
        $maxPrice = null;

        // Find min price (first available size)
        foreach ($sizes_order as $size) {
            foreach ($variants as $v) {
                if ($v->prv_Size == $size) {
                    $minPrice = $v->prv_price;
                    break 2;
                }
            }
        }

        // Find max price (last available size)
        for ($i = count($sizes_order) - 1; $i >= 0; $i--) {
            foreach ($variants as $v) {
                if ($v->prv_Size == $sizes_order[$i]) {
                    $maxPrice = $v->prv_price;
                    break 2;
                }
            }
        }

        $product->min_price = $minPrice ?? 0;
        $product->max_price = $maxPrice ?? 0;

        // Set main image
        $product->main_image = !empty($product->pri_Thumbnail) ? $product->pri_Thumbnail : null;
    }

    return $products;
}




    public function getLast7DaysOrdersCount()
    {
        $sevenDaysAgo = date('Y-m-d 00:00:00', strtotime('-7 days'));
        $today = date('Y-m-d 23:59:59');

        return $this->db->table('order_detail')
            ->where('od_createdon >=', $sevenDaysAgo)
            ->where('od_createdon <=', $today)
            ->countAllResults();
    }




}

?>