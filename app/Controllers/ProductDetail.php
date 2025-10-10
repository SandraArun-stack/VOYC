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

}
