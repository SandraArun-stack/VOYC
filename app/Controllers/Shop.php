<?php
namespace App\Controllers;

use App\Models\ShopModel;
use CodeIgniter\Controller;

class Shop extends Controller
{
    protected $session;
    protected $request;
    protected $ShopModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->ShopModel = new ShopModel();
    }

    //copy yesterday

    public function index($segment = null)
    {
        $uri = service('uri');

        if (empty($segment)) {
            $segment = $uri->getSegment(1);
        }

        if ($this->request->isAJAX()) {
            $subcategoryIds = $this->request->getPost('subcategory_id') ?? [];

            $mainCategory = $this->request->getPost('main_category');
            $minPrice = $this->request->getPost('min_price');
            $maxPrice = $this->request->getPost('max_price');
            $sizes = $this->request->getPost('sizes');

            $page = (int) $this->request->getPost('page') ?: 1;
            $limit = 9;
            $offset = ($page - 1) * $limit;

            if (empty($subcategoryIds)) {
                $mainCategory = $this->request->getPost('main_category');
                $subcategoryIds = $this->ShopModel->getItemIfNoSubProducts($mainCategory);
            }

            if (empty($subcategoryIds)) {
                $subcategoryIds = [0];
            }

            $data = $this->ShopModel->getProductsBySubcategory($subcategoryIds, $mainCategory, $minPrice, $maxPrice,$sizes, $limit, $offset);

            if (empty($data['products'])) {
                return $this->response->setJSON(['status' => 'empty']);
            }


            return $this->response->setJSON([
                'status' => 'success',
                'filtered_products' => $data['products'],
                'totalPages' => $data['totalPages'],
                'currentPage' => $page
            ]);
        }

        // Normal page load (GET)
        if (!in_array($segment, ['men', 'women'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $display_item = $this->ShopModel->getDisplayedItems($segment);
        $categories = $this->ShopModel->getUniqueCategoriesWithSub($segment);

        $data = [
            'category' => $segment,
            'title' => ucfirst($segment) . ' Shop',
            'breadcrumb' => ucfirst($segment),
            'display_item' => $display_item,
            'categories' => $categories
        ];

        return view('common/header')
            . view('shop', $data)
            . view('common/footer')
            . view('pagescripts/shopjs');
    }



}