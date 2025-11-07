<?php
namespace App\Controllers;

use App\Models\ProductDetailModel;
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
    }

    public function index($prId = null, $priId = null)
    {
        if (!$prId && !$priId) {
            return redirect()->to(base_url('/'));
        }

        $product = $this->ProductDetailModel->get_prd_Details($prId, $priId);
        // print_r($product); exit;

        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Product not found");
        }

        $data = [
            'product' => $product,
            'images' => $product['images']
        ];

        return view('common/header')
            . view('product-details', $data)
            . view('common/footer')
            . view('pagescripts/productdetailsjs');
    }
    public function getColorImage($priId)
    {
        $image = $this->ProductDetailModel->getImageByColor($priId);

        if ($image && !empty($image['pri_Thumbnail'])) {
            $smallImages = json_decode($image['pri_File_Name']);
            return $this->response->setJSON([
                'image_url' => base_url('uploads/productmedia/' . $image['pri_Thumbnail']),
                'small_image_urls' => $smallImages ? array_map(function ($fileName) {
                    return base_url('uploads/productmedia/' . $fileName);
                }, $smallImages) : []
            ]);
        }

        return $this->response->setJSON([
            'image_url' => base_url('uploads/productmedia/default.jpg'),
            'small_image_url' => base_url('uploads/productmedia/default.jpg')
        ]);
    }

    public function getSizesByColor($priId)
    {
        $sizes = $this->ProductDetailModel->getSizesByColor($priId);
        return $this->response->setJSON($sizes);
    }



    public function addToCart()
    {
        $custId   = $this->request->getPost('cust_Id');
        $prId     = $this->request->getPost('pr_Id');
        $priId    = $this->request->getPost('pri_Id');
        $prvId    = $this->request->getPost('prv_Id');
        $designId = $this->request->getPost('design_Id');
        $quantity = $this->request->getPost('cart_Quantity') ?? 1;
        $price = $this->request->getPost('cart_Price');

        if (
            !isset($custId) || 
            !isset($prId)   || 
            !isset($priId)  || 
            !isset($prvId)
        ) {
            return $this->response->setJSON(['status' => 0, 'message' => 'Missing required data']);
        }

        $data = [
            'cust_Id'       => $custId,
            'pr_Id'         => $prId,
            'pri_Id'        => $priId,
            'prv_Id'        => $prvId,
            'design_Id'     => $designId,
            'cart_Quantity' => $quantity,
            'cart_Price'         => $price
        ];

        $result = $this->ProductDetailModel->saveToCart($data);

        if ($result === 'inserted' || $result === 'updated') {
            return $this->response->setJSON(['status' => 1, 'message' => 'Added to cart successfully']);
        }

        return $this->response->setJSON(['status' => 0, 'message' => 'Error adding to cart']);
    }


}
