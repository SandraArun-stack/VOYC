<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Admin\ProductImageModel;

class ProductImage extends BaseController
{

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->input = \Config\Services::request();
        $this->productimageModel = new ProductImageModel();
    }

    public function index()
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to(base_url('admin'));
        }

        $allproductimages = $this->productimageModel->getAllProductImages();
        $data['productimages'] = $allproductimages;
        $template = view('Admin/common/header');
        $template .= view('Admin/common/leftmenu');
        $template .= view('Admin/productimage', $data);
        $template .= view('Admin/common/footer');
        return $template;

    }
    public function viewimage($pr_id = null)
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to(base_url('admin'));
        }

        $allproductimages = $this->productimageModel->getAllProductImages();
        $data['productimages'] = $allproductimages;
        $data['pr_id'] = $pr_id;
        $template = view('Admin/common/header');
        $template .= view('Admin/common/leftmenu');
        $template .= view('Admin/productimage', $data);
        $template .= view('Admin/common/footer');
        return $template;

    }
    public function addProductImage($pr_id = null)
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to(base_url('admin'));
        }

        $data = [];
        // if you want product info/images for that product
        if ($pr_id !== null) {
            $data['productimages'] = $this->productimageModel->getProductImages($pr_id);
            $data['pr_id'] = $pr_id; // pass product id to view
        } else {
            $data['productimages'] = $this->productimageModel->getProductImages();
        }

        $template = view('Admin/common/header');
        $template .= view('Admin/common/leftmenu');
        $template .= view('Admin/productimage_add', $data);
        $template .= view('Admin/common/footer');
        $template .= view('Admin/page_scripts/productimagejs');
        return $template;
    }

    

    public function save()
    {
        $colorsData = $this->request->getPost('colors');
        $pr_id = $this->request->getPost('pr_id');

        if (!empty($colorsData)) {
            foreach ($colorsData as $colorIndex => $colorGroup) {
                $color = $colorGroup['color'] ?? null;
                $sizes = $colorGroup['sizes'] ?? [];
                $prices = $colorGroup['prices'] ?? [];
                $stock = $colorGroup['stock'] ?? 0;         // <-- new
                $reset_stock = $colorGroup['reset_stock'] ?? 0;
                $imagesUploaded = [];

                // --- Handle file uploads ---
                if (isset($_FILES['colors']['name'][$colorIndex]['images'])) {
                    $fileNames = $_FILES['colors']['name'][$colorIndex]['images'];
                    $tmpNames = $_FILES['colors']['tmp_name'][$colorIndex]['images'];
                    $errors = $_FILES['colors']['error'][$colorIndex]['images'];

                    for ($i = 0; $i < count($fileNames); $i++) {
                        if ($errors[$i] === 0) {
                            $ext = pathinfo($fileNames[$i], PATHINFO_EXTENSION);
                            $newName = uniqid('', true) . '.' . $ext;
                            $destination = FCPATH . 'uploads/productmedia/' . $newName;

                            if (move_uploaded_file($tmpNames[$i], $destination)) {
                                $imagesUploaded[] = $newName;
                            }
                        }
                    }
                }

                // --- Insert into product_image ---
                $imageData = [
                    'pr_Id' => $pr_id,
                    'pri_Thumbnail' => !empty($imagesUploaded) ? $imagesUploaded[0] : null,
                    'pri_File_Name' => !empty($imagesUploaded) ? json_encode($imagesUploaded) : null,
                    'color_details' => json_encode(['color' => $color]),
                    'stock' => $stock,               // <-- new column in product_images table
                    'reset_stock' => $reset_stock,
                    'pri_createdon' => date('Y-m-d H:i:s'),
                    'pri_createdby' => $this->session->get('ad_uid'),
                    'pri_Status' => 1
                ];

                $pri_id = $this->productimageModel->insertProductImages($imageData);

                // --- Insert sizes + prices into product_variants ---
                if (!empty($sizes)) {
                    foreach ($sizes as $size) {
                        $variantData = [
                            'pr_id' => $pr_id,
                            'pri_id' => $pri_id,
                            'prv_size' => $size,
                            'prv_price' => $prices[$size] ?? 0,
                            'stock' => $stock,              
                            'reset_stock' => $reset_stock
                        ];
                        $this->productimageModel->insertVariant($variantData);
                    }
                }

            }
        }

        return $this->response->setJSON(['status' => 'success']);
    }

}