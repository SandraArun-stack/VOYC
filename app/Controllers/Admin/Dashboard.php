<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Admin\DashboardModel;


class Dashboard extends BaseController
{

	public function __construct()
	{
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->dashboardModel = new \App\Models\Admin\DashboardModel();

	}
	public function index()
	{
		if (!$this->session->get('ad_uid')) {
				return redirect()->to(base_url('admin'));
			}

		if (!$this->session->get('ad_uid')) {
			redirect()->to(base_url());
		}



		$latestOrderCount = $this->dashboardModel->getLatestOrderCount();
		$totalOrderCount = $this->dashboardModel->getTotalOrderCount();
		$totalCustomerCount = $this->dashboardModel->getTotalCustomerCount();
		$annualRevenue = $this->dashboardModel->getAnnualRevenue();
		$last7days_orders = $this->dashboardModel->getLast7DaysOrdersCount();


		$todaysOrders = $this->dashboardModel->getTodaysOrders();
		$latestProducts = $this->dashboardModel->getLatestProducts();
		// Decode images for each product
		// After $latestProducts = $this->dashboardModel->getLatestProducts();

foreach ($latestProducts as &$product) {
    $product->main_image = null;

    if (!empty($product->pri_Thumbnail)) {
        // Try decode — if valid JSON array, take first element
        $decoded = json_decode($product->pri_Thumbnail, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded[0])) {
            $product->main_image = $decoded[0];
        } else {
            // Not JSON or decode failed -> treat as plain filename
            $product->main_image = $product->pri_Thumbnail;
        }
    }
}




		$template = view('Admin/common/header');
		$template .= view('Admin/common/leftmenu');
		$template .= view('Admin/dashboard', [
			'latestOrderCount' => $latestOrderCount,
			'totalOrderCount' => $totalOrderCount,
			'totalCustomerCount' => $totalCustomerCount,
			'annualRevenue' => $annualRevenue,
			'todaysOrders' => $todaysOrders,
			'latestProducts' => $latestProducts,
			'last7days_orders' => $last7days_orders
		]);
		$template .= view('Admin/common/footer');
		return $template;

	}



}