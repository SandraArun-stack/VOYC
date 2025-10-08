<?php
namespace App\Controllers;

use App\Models\NewProductModel;
use CodeIgniter\Controller;

class ProductDetail extends Controller
{
    protected $session;
    protected $request;
    protected $NewProductModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->NewProductModel = new NewProductModel();
    }

    public function index($prId = null, $priId = null)
    {
        if (!$prId && !$priId) {
            return redirect()->to(base_url('/'));
        }

        $product = $this->NewProductModel->get_prd_Details($prId, $priId);

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
        // echo "yes"; exit();
        $image = $this->NewProductModel->getImageByColor($priId);

        if ($image && !empty($image['pri_Thumbnail'])) {
            return $this->response->setJSON([
                'image_url' => base_url('uploads/productmedia/' . $image['pri_Thumbnail'])
            ]);
        }

        return $this->response->setJSON([
            'image_url' => base_url('uploads/productmedia/default.jpg')
        ]);
    }

}
