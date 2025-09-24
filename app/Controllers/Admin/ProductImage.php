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
        $template .= view('Admin/page_scripts/productimagejs');
        return $template;

    }

    public function ajaxList()
    {

        $model = new \App\Models\Admin\ProductImageModel();
        $data = $model->getDatatables();
        $total = $model->countAll();
        $filtered = $model->countFiltered();

        // Aggregate variants per product image
        $aggregated = [];
        foreach ($data as $row) {
            $id = $row['pri_Id'];

            if (!isset($aggregated[$id])) {
                $aggregated[$id] = [
                    'pri_Id' => $row['pri_Id'],
                    'pr_Id' => $row['pr_id'],
                    'pr_Name' => $row['pr_name'] ?? 'N/A',
                    'colors' => json_decode($row['color_details'], true)['color'] ?? 'N/A',
                    'sizes' => [],
                    'prices' => [],
                    'stocks' => [],
                    'reset_stocks' => [],
                    'pri_Status' => $row['pri_Status'],
                ];
            }

            // Collect variants
            $aggregated[$id]['sizes'][] = $row['prv_Size'] ?? 'N/A';
            $aggregated[$id]['prices'][] = $row['prv_price'] ?? 'N/A';
            $aggregated[$id]['stocks'][] = $row['stock'] ?? 'N/A';
            $aggregated[$id]['reset_stocks'][] = $row['reset_stock'] ?? 'N/A';
        }

        // Convert arrays to comma-separated strings and add actions/status
        foreach ($aggregated as &$row) {
            $row['sizes'] = implode(', ', $row['sizes']);
            $row['prices'] = implode(', ', $row['prices']);
            $row['stocks'] = implode(', ', $row['stocks']);
            $row['reset_stocks'] = implode(', ', $row['reset_stocks']);

            // Status toggle
            $row['status_switch'] = '<div class="form-check form-switch">
            <input class="form-check-input checkactive"
                   type="checkbox"
                   id="statusSwitch-' . $row['pri_Id'] . '"
                   value="' . $row['pri_Id'] . '" ' . ($row['pri_Status'] == 1 ? 'checked' : '') . '>
            <label class="form-check-label pl-0 label-check"
                   for="statusSwitch-' . $row['pri_Id'] . '"></label>
        </div>';

            // Action buttons
            $baseurl = base_url('admin/productimage/edit/');
            $row['actions'] = '
                    <a href="'.$baseurl.$row['pri_Id'] .'"><button type="button" class="btn btn-sm btn-primary" data-id="' . $row['pri_Id'] . '">
                        <i class="bi bi-pencil-square"></i>
                    </button></a>&nbsp;
                    <i class="bi bi-trash text-danger icon-clickable" style="cursor: pointer;" 
                    onclick="confirmDelete(' . $row['pri_Id'] . ')"></i>&nbsp;';
        }

        return $this->response->setJSON([
            'draw' => intval($this->request->getPost('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => array_values($aggregated)
        ]);
    }


    public function edit($pri_id = null)
    {
        
        // Check if the user is authorized
        if (!$this->session->get('ad_uid')) {
            return $this->response->setJSON(['status' => 0, 'msg' => 'Unauthorized']);
        }

        // Check if the product image ID is valid
        if ($pri_id === null) {
            return $this->response->setJSON(['status' => 0, 'msg' => 'Invalid Product Image ID']);
        }

        // Fetch product image and variants by ID
        $productImage = $this->productimageModel->getProductImageById($pri_id);
        $variants = $this->productimageModel->getVariantsByPriId($pri_id);

        // Check if the product image exists
        if (!$productImage) {
            return $this->response->setJSON(['status' => 0, 'msg' => 'Product Image not found']);
        }

        // Prepare the necessary data
        $colorsData = [];
        $colorDetails = json_decode($productImage['color_details'], true) ?: [];
        $colorName = $colorDetails['color'] ?? '';

        $prices = [];
        $stock = [];
        $reset_stock = [];

        foreach ($variants as $v) {
            $size = $v['prv_Size'] ?? '';
            if ($size) {
                $prices[$size] = $v['prv_price'] ?? '';
                $stock[$size] = $v['stock'] ?? '';
                $reset_stock[$size] = $v['reset_stock'] ?? '';
            }
        }

        // Prepare images data
        $images = !empty($productImage['pri_File_Name']) ? json_decode($productImage['pri_File_Name'], true) : [];
        $images = array_map(fn($img) => base_url('uploads/productmedia/' . $img), $images);

        // Prepare color data for the view
        $colorsData[] = [
            'color' => $colorName,
            'sizes' => array_keys($prices),
            'prices' => $prices,
            'stock' => $stock,
            'reset_stock' => $reset_stock,
            'images' => $images
        ];

        // Check if this is an AJAX request (i.e., expects JSON response)
       /* if ($this->request->isAJAX()) {
            // Return JSON data
            return $this->response->setJSON([
                'status' => 1,
                'productImage' => $productImage,
                'colorsData' => $colorsData
            ]);
        }*/
       
        // Otherwise, render the HTML page
        $data = [
            'productImage' => $productImage,
            'colorsData' => $colorsData
        ];
        echo '<pre>';
        //print_r($data['colorsData'][0]['prices']['S']);
        echo '</pre>';
//exit();
      //  print_r($data);
        // Render the HTML template
        $template = view('Admin/common/header');
        $template .= view('Admin/common/leftmenu');
        $template .= view('Admin/productimage_add', $data);  // pass data to your specific view
        $template .= view('Admin/common/footer');
        $template .= view('Admin/page_scripts/productimagejs');

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
        $template .= view('Admin/page_scripts/productimagejs');
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
                $stock = $colorGroup['stock'] ?? [];
                $reset_stock = $colorGroup['reset_stock'] ?? [];
                $imagesUploaded = [];

                // --- Handle uploaded files ---
                if (!empty($_FILES['colors']['name'][$colorIndex]['images'])) {
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
                    'pri_Thumbnail' => $imagesUploaded[0] ?? null,
                    'pri_File_Name' => !empty($imagesUploaded) ? json_encode($imagesUploaded) : null,
                    'color_details' => json_encode(['color' => $color]),
                    'pri_createdon' => date('Y-m-d H:i:s'),
                    'pri_createdby' => $this->session->get('ad_uid'),
                    'pri_Status' => 1
                ];

                $pri_id = $this->productimageModel->insertProductImages($imageData);

                // --- Insert sizes + prices + stock per size ---
                if (!empty($sizes)) {
                    foreach ($sizes as $size) {
                        $variantData = [
                            'pr_id' => $pr_id,
                            'pri_id' => $pri_id,
                            'prv_Size' => $size,
                            'prv_price' => $prices[$size] ?? 0,
                            'stock' => $stock[$size] ?? 0,
                            'reset_stock' => $reset_stock[$size] ?? 0
                        ];
                        $this->productimageModel->insertVariant($variantData);
                    }
                }
            }
        }

        return $this->response->setJSON(['status' => 'success']);
    }
    public function delete($pri_id = null)
    {
        if (!$this->session->get('ad_uid')) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Unauthorized']);
        }

        if ($pri_id === null) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Invalid ID']);
        }

        $deleted = $this->productimageModel->deleteProductImage($pri_id);

        if ($deleted) {
            return $this->response->setJSON(['status' => 'success', 'msg' => 'Deleted successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Delete failed']);
        }
    }


}