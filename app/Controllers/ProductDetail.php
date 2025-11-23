<?php
namespace App\Controllers;

use App\Models\ProductDetailModel;
use App\Models\CartModel;
use CodeIgniter\Controller;

class ProductDetail extends Controller
{
    protected $session;
    protected $request;
    protected $ProductDetailModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->ProductDetailModel = new ProductDetailModel();
        $this->CartModel = new CartModel();
    }

    public function index($prId = null, $priId = null)
    {
        $session = session();
        $userId = $session->get('user_id');
        $cartCount = $this->CartModel->getCartItemCount($userId);

        if (!$prId && !$priId) {
            return redirect()->to(base_url('/'));
        }

        $product = $this->ProductDetailModel->get_prd_Details($prId, $priId);
        $relatesProducts = $this->ProductDetailModel->getRelatedProducts($prId, $priId);
        // print_r($product); exit;

        if (!$product) {
             return redirect()->to(base_url('/'));
        }

        $data = [
            'product' => $product,
            'images' => $product['images'],
             'relatedProducts' => $relatesProducts 
        ];

        return view('common/header', ['cartCount' => $cartCount])
            . view('product-details', $data)
            . view('common/footer')
            . view('pagescripts/productdetailsjs');
    }

    public function getColorImage($priId)
    {
        $image = $this->ProductDetailModel->getImageByColor($priId);

        if (!$image) {
            return $this->response->setJSON([
                'image_url' => base_url('uploads/productmedia/default.jpg'),
                'small_image_urls' => []
            ]);
        }

        $allImages = [];

        // Decode and collect pri_File_Name
        if (!empty($image['pri_File_Name'])) {
            $files = json_decode($image['pri_File_Name'], true);
            if (is_array($files)) {
                foreach ($files as $file) {
                    $allImages[] = base_url('uploads/productmedia/' . $file);
                }
            }
        }

        // Decode and collect pri_Sleev_Name
        if (!empty($image['pri_Sleev_Name'])) {
            $sleeves = json_decode($image['pri_Sleev_Name'], true);
            if (is_array($sleeves)) {
                foreach ($sleeves as $file) {
                    $allImages[] = base_url('uploads/productmedia/' . $file);
                }
            }
        }

        // Add RSleeve_Img and LSleeve_Img if valid
        foreach (['RSleeve_Img', 'LSleeve_Img'] as $field) {
            if (!empty($image[$field]) && $image[$field] !== '[]') {
                $allImages[] = base_url('uploads/productmedia/' . $image[$field]);
            }
        }

        // Fallback
        if (empty($allImages)) {
            $allImages[] = base_url('uploads/productmedia/' . ($image['pri_Thumbnail'] ?? 'default.jpg'));
        }

        return $this->response->setJSON([
            'image_url' => base_url('uploads/productmedia/' . ($image['pri_Thumbnail'] ?? 'default.jpg')),
            'small_image_urls' => $allImages,
            'video_url' => !empty($image['pri_Video']) ? base_url('uploads/productmedia/' . $image['pri_Video']) : null
        ]);
    }


    public function getSizesByColor($priId)
    {
        $sizes = $this->ProductDetailModel->getSizesByColor($priId);
        return $this->response->setJSON($sizes);
    }



    public function addToCart()
    {
        $custId = $this->request->getPost('cust_Id');
        $prId = $this->request->getPost('pr_Id');
        $priId = $this->request->getPost('pri_Id');
        $prvId = $this->request->getPost('prv_Id');
        $designId = $this->request->getPost('design_Id');
        $quantity = $this->request->getPost('cart_Quantity') ?? 1;
        $cart_Price = $this->request->getPost('cart_Price');
        $cart_Size = $this->request->getPost('cart_Size');
        if (
            !isset($custId) ||
            !isset($prId) ||
            !isset($priId) ||
            !isset($prvId)
        ) {
            return $this->response->setJSON(['status' => 0, 'message' => 'Missing required data']);
        }

        $data = [
            'cust_Id' => $custId,
            'pr_Id' => $prId,
            'pri_Id' => $priId,
            'prv_Id' => $prvId,
            'design_Id' => $designId,
            'cart_Quantity' => $quantity,
            'cart_Price' => $cart_Price,
            'cart_Size' => $cart_Size
        ];

        $result = $this->ProductDetailModel->saveToCart($data);

        if ($result === 'inserted' || $result === 'updated') {
            return $this->response->setJSON(['status' => 1, 'message' => 'Added to cart successfully']);
        }

        return $this->response->setJSON(['status' => 0, 'message' => 'Error adding to cart']);
    }


}
