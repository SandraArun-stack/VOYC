<?php
namespace App\Controllers;

use App\Models\ShopModel;
use App\Models\CartModel;
use CodeIgniter\Controller;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\GameMappingModel;

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
        $this->CartModel = new CartModel();
        $this->PlayersModel = new PlayersModel();
        $this->GameMappingModel = new GameMappingModel();
    }


    public function index($segment = null)
    {
        $session = session();
        $userId = $session->get('user_id');
        $cartCount = $this->CartModel->getCartItemCount($userId);

        $uri = service('uri');
        $search = $this->request->getGet('search'); //  get ?search keyword from URL

        if (empty($segment)) {
            $segment = $uri->getSegment(1);
        }

        //leaderboard Count
        $today = date('Y-m-d');
        $todayLimit = $this->GameMappingModel->getTodayLeaderboardCount($today);
        $todayLimit = intval($todayLimit);

        $result = $this->PlayersModel->getTodayPlayers($today, $todayLimit, session()->get('user_id'));

        // Handle AJAX filtering
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
                $subcategoryIds = $this->ShopModel->getItemIfNoSubProducts($mainCategory);
            }

            if (empty($subcategoryIds)) {
                $subcategoryIds = [0];
            }

            $data = $this->ShopModel->getProductsBySubcategory(
                $subcategoryIds,
                $mainCategory,
                $minPrice,
                $maxPrice,
                $sizes,
                $limit,
                $offset
            );

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

        //  Handle normal page load or search
        if ($search) {
            // If search term provided, get matching products (from all categories)
            $display_item = $this->ShopModel->searchProducts($search);
            $categories = $this->ShopModel->getUniqueCategoriesWithSub('men'); // fallback or default
            $data = [
                'category' => 'search',
                'title' => 'Search Results',
                'breadcrumb' => $search,
                'display_item' => $display_item,
                'categories' => $categories,
                'searchTerm' => $search
            ];

            return view('common/header', [
                'cartCount' => $cartCount,
                'players' => $result['players'],
                'lastPlayer' => $result['lastPlayer']
            ])
                . view('shop', $data)
                . view('common/footer')
                . view('pagescripts/shopjs');
        }

        // Normal men/women category load
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
            'categories' => $categories,
            'searchTerm' => ''
        ];

        return view('common/header', [
            'cartCount' => $cartCount,
            'players' => $result['players'],
            'lastPlayer' => $result['lastPlayer']
        ])
            . view('shop', $data)
            . view('common/footer')
            . view('pagescripts/shopjs');
    }



}