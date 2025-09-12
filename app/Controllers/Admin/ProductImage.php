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
        $data['productimages'] =  $allproductimages;
        $template = view('Admin/common/header');
		$template.= view('Admin/common/leftmenu');
		$template.= view('Admin/productimage', $data );
		$template.= view('Admin/common/footer');
        return $template;

    }
     public function viewimage($pr_id=null)
    {
		 if (!$this->session->get('ad_uid')) {
           return redirect()->to(base_url('admin'));
    }

        $allproductimages = $this->productimageModel->getAllProductImages();
        $data['productimages'] =  $allproductimages;
        $data['pr_id'] = $pr_id;
        $template = view('Admin/common/header');
		$template.= view('Admin/common/leftmenu');
		$template.= view('Admin/productimage', $data );
		$template.= view('Admin/common/footer');
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

    $template  = view('Admin/common/header');
    $template .= view('Admin/common/leftmenu');
    $template .= view('Admin/productimage_add', $data);
    $template .= view('Admin/common/footer');
    $template .= view('Admin/page_scripts/productimagejs');
    return $template;
}

public function save()
{
    $colorsData = $this->request->getPost('colors');
    $pr_id      = $this->request->getPost('pr_id');

    $finalData = [];

    if (!empty($colorsData)) {
        foreach ($colorsData as $colorIndex => $colorGroup) {
            $color = $colorGroup['color'] ?? null;
            $sizes = $colorGroup['sizes'] ?? [];
            $imagesUploaded = [];

            // Handle file uploads
            if (isset($_FILES['colors']['name'][$colorIndex]['images'])) {
                $fileNames = $_FILES['colors']['name'][$colorIndex]['images'];
                $tmpNames  = $_FILES['colors']['tmp_name'][$colorIndex]['images'];
                $errors    = $_FILES['colors']['error'][$colorIndex]['images'];

                for ($i = 0; $i < count($fileNames); $i++) {
                    if ($errors[$i] === 0) {
                        $ext = pathinfo($fileNames[$i], PATHINFO_EXTENSION);
                        $newName = uniqid() . '.' . $ext;
                        $destination = FCPATH . 'uploads/productmedia/' . $newName;

                        if (move_uploaded_file($tmpNames[$i], $destination)) {
                            $imagesUploaded[] = 'uploads/productmedia/' . $newName;
                        }
                    }
                }
            }

            $finalData[] = [
                'color'  => $color,
                'sizes'  => $sizes,
                'images' => $imagesUploaded
            ];
        }
    }

    // Prepare data
    $data = [
        'pr_Id'         => $pr_id,
        'color_details' => json_encode($finalData),
        'pri_createdon' => date('Y-m-d H:i:s'),
        'pri_createdby' => $this->session->get('ad_uid'),
        'pri_Status'    => 1
    ];
    // Insert using model
    $this->productimageModel->insertProductImages($data);

    return $this->response->setJSON(['status' => 'success']);
}





    // public function saveProductImage()
    // {
    //     $pr_id = $this->request->getPost('pr_id');
    //     $file_type = $this->request->getPost('file_type');
    //     $files = $this->request->getFiles();
    
    //     if (!$pr_id || empty($files['media_files'])) {
    //         return $this->response->setJSON(['status' => 0, 'msg' => 'Product And Files Are Required.']);
    //     }
    
    //     $mediaFiles = $files['media_files'];
    //     $uploadData = [];
    
    //     if (is_array($mediaFiles)) {
    //         foreach ($mediaFiles as $file) {
    //             if ($file->isValid() && !$file->hasMoved()) {
    //                 $newName = $file->getRandomName();
    //                 $file->move(FCPATH . 'uploads/productmedia/', $newName);
    
    //                 $uploadData[] = [
    //                     'name' => $newName,
    //                     'type' => $file_type
    //                 ];
    //             }
    //         }
    //     }
    
    //     if (!empty($uploadData)) {
    //         $data = [
    //             'pr_id' => $pr_id,
    //             'pri_File_Type'=> $file_type,
    //             'pri_Thumbnail' => json_encode($uploadData),
    //             'pri_Status' =>1,
    //             'pri_createdon' => date('Y-m-d H:i:s'),
    //             'pri_createdby' => $this->session->get('ad_uid')
    //         ];
    
    //         $this->productimageModel->productimageInsert($data);
    
    //         return $this->response->setJSON(['status' => 1, 'msg' => 'Media Uploaded Successfully.']);
    //     } else {
    //         return $this->response->setJSON(['status' => 0, 'msg' => 'No Valid Files Uploaded.']);
    //     }
    // }
    
    
}