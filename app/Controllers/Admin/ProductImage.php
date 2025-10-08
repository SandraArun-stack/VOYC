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
         $data['pr_id'] = null;

        $template = view('Admin/common/header');
        $template .= view('Admin/common/leftmenu');
        $template .= view('Admin/productimage', $data);
        $template .= view('Admin/common/footer');
        $template .= view('Admin/page_scripts/productimagejs');
        return $template;

    }

public function ajaxList()
{
    $pr_id = $this->request->getPost('pr_id'); 

    $model = new \App\Models\Admin\ProductImageModel();
    $data = $model->getDatatables($pr_id);
    $total = $model->countAll($pr_id);
    $filtered = $model->countFiltered($pr_id);

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
       $row['actions'] = '
    <a href="' . base_url('admin/product/image/edit/' . $row['pr_Id'].'/' . $row['pri_Id']) . '">
        <i class="bi bi-pencil-square"></i>
    </a>&nbsp;
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




   public function viewimage($pr_id = null)
{
    if (!$this->session->get('ad_uid')) {
        return redirect()->to(base_url('admin'));
    }

    if ($pr_id === null) {
        return redirect()->to(base_url('admin/products'))->with('error', 'Product not selected');
    }

    // Only get images for this product
    $productImages = $this->productimageModel->getProductImages($pr_id);

    $data['productimages'] = $productImages;
    $data['pr_id'] = $pr_id;

    $template = view('Admin/common/header');        
    $template .= view('Admin/common/leftmenu');
    $template .= view('Admin/productimage', $data);
    $template .= view('Admin/common/footer');
    $template .= view('Admin/page_scripts/productimagejs');

    return $template;
}



 
    public function addProductImage($pr_id = null, $pri_id = null)
{
    if (!$this->session->get('ad_uid')) {
        return redirect()->to(base_url('admin'));
    }

    $data = [];

    // Add Mode → only product ID exists
    if ($pr_id !== null && $pri_id === null) {
        $data['mode'] = 'add';
        $data['pr_id'] = $pr_id;
        $data['productimages'] = []; // empty, since we're adding new image
    }
    // Edit Mode → both product ID and image ID exist
    elseif ($pr_id !== null && $pri_id !== null) {
        $data['mode'] = 'edit';
        $data['pr_id'] = $pr_id;
        $data['pri_id'] = $pri_id;
        $data['productimages'] = $this->productimageModel->getProductImages($pri_id);
        $data['variants'] = $this->productimageModel->getVariantsByPriId($pri_id);
    }
    // No valid product ID → redirect or show 404
    else {
        return redirect()->to(base_url('admin/product'));
    }

    // Load views
    $template  = view('Admin/common/header');
    $template .= view('Admin/common/leftmenu');
    $template .= view('Admin/productimage_add', $data);
    $template .= view('Admin/common/footer');
    $template .= view('Admin/page_scripts/productimagejs');
    return $template;
}




// public function save()
// {
//     $colorsData = $this->request->getPost('colors');
//     $pr_id = $this->request->getPost('pr_id');

//     if (empty($colorsData) || empty($pr_id)) {
//         return $this->response->setJSON([
//             'status' => 'error',
//             'msg' => 'Please provide all required data.'
//         ]);
//     }

//     $insertedAny = false; // Track if any product image is inserted

//     foreach ($colorsData as $colorIndex => $colorGroup) {

//         $color = $colorGroup['color'] ?? null;
//         $sizes = $colorGroup['sizes'] ?? [];
//         $prices = $colorGroup['prices'] ?? [];
//         $stock = $colorGroup['stock'] ?? [];
//         $reset_stock = $colorGroup['reset_stock'] ?? [];
//         $imagesUploaded = [];

//         // --- Validate: at least one image uploaded ---
//         if (empty($_FILES['colors']['name'][$colorIndex]['images'][0])) {
//             continue; // Skip this group if no image
//         }

//         // --- Handle uploaded files ---
//         $fileNames = $_FILES['colors']['name'][$colorIndex]['images'];
//         $tmpNames = $_FILES['colors']['tmp_name'][$colorIndex]['images'];
//         $errors = $_FILES['colors']['error'][$colorIndex]['images'];

//         for ($i = 0; $i < count($fileNames); $i++) {
//             if ($errors[$i] === 0) {
//                 $ext = pathinfo($fileNames[$i], PATHINFO_EXTENSION);
//                 $newName = uniqid('', true) . '.' . $ext;
//                 $destination = FCPATH . 'uploads/productmedia/' . $newName;
//                 if (move_uploaded_file($tmpNames[$i], $destination)) {
//                     $imagesUploaded[] = $newName;
//                 }
//             }
//         }

//         if (empty($imagesUploaded)) {
//             continue; // Skip if no valid images uploaded
//         }

//         // --- Insert into product_image ---
//         $imageData = [
//             'pr_Id' => $pr_id,
//             'pri_Thumbnail' => $imagesUploaded[0],
//             'pri_File_Name' => json_encode($imagesUploaded),
//             'color_details' => json_encode(['color' => $color]),
//             'pri_createdon' => date('Y-m-d H:i:s'),
//             'pri_createdby' => $this->session->get('ad_uid'),
//             'pri_Status' => 1
//         ];

//         $pri_id = $this->productimageModel->insertProductImages($imageData);

//         // --- Insert sizes + prices + stock per size ---
//         if (!empty($sizes)) {
//             foreach ($sizes as $size) {
//                 $variantData = [
//                     'pr_id' => $pr_id,
//                     'pri_id' => $pri_id,
//                     'prv_Size' => $size,
//                     'prv_price' => $prices[$size] ?? 0,
//                     'stock' => $stock[$size] ?? 0,
//                     'reset_stock' => $reset_stock[$size] ?? 0
//                 ];
//                 $this->productimageModel->insertVariant($variantData);
//             }
//         }

//         $insertedAny = true;
//     }

//     if ($insertedAny) {
//         return $this->response->setJSON([
//             'status' => 'success',
//             'msg' => 'Product Images Saved Successfully!'
//         ]);
//     }

//     return $this->response->setJSON([
//         'status' => 'error',
//         'msg' => 'Please Upload at Least One Image for Each Color Group.'
//     ]);
// }




public function save()
{
    $colorsData = $this->request->getPost('colors');
    $pr_id = $this->request->getPost('pr_id');

    if (empty($colorsData) || empty($pr_id)) {
        return $this->response->setJSON([
            'status' => 'error',
            'msg' => 'Please provide all required data.'
        ]);
    }

    $insertedAny = false;

    foreach ($colorsData as $colorIndex => $colorGroup) {

        $color = $colorGroup['color'] ?? null;
        $sizes = $colorGroup['sizes'] ?? [];
        $prices = $colorGroup['prices'] ?? [];
        $stock = $colorGroup['stock'] ?? [];
        $reset_stock = $colorGroup['reset_stock'] ?? [];

        // Check if we are editing an existing product image
        $pri_id = $colorGroup['pri_id'] ?? null;
        $existingData = $pri_id ? $this->productimageModel->find($pri_id) : null;

        $thumbnailUploaded = [];
        $sideUploaded = [];

        // --- Handle new Thumbnail upload ---
        if (!empty($_FILES['colors']['name'][$colorIndex]['images'][0])) {
            $fileNames = $_FILES['colors']['name'][$colorIndex]['images'];
            $tmpNames = $_FILES['colors']['tmp_name'][$colorIndex]['images'];
            $errors = $_FILES['colors']['error'][$colorIndex]['images'];

            for ($i = 0; $i < count($fileNames); $i++) {
                if ($errors[$i] === 0) {
                    $ext = pathinfo($fileNames[$i], PATHINFO_EXTENSION);
                    $newName = uniqid('', true) . '.' . $ext;
                    $destination = FCPATH . 'uploads/productmedia/' . $newName;
                    if (move_uploaded_file($tmpNames[$i], $destination)) {
                        $thumbnailUploaded[] = $newName;
                    }
                }
            }
        }

        // --- Handle new Side Images upload ---
        if (!empty($_FILES['colors']['name'][$colorIndex]['side_image'][0])) {
            $fileNames = $_FILES['colors']['name'][$colorIndex]['side_image'];
            $tmpNames = $_FILES['colors']['tmp_name'][$colorIndex]['side_image'];
            $errors = $_FILES['colors']['error'][$colorIndex]['side_image'];

            for ($i = 0; $i < count($fileNames); $i++) {
                if ($errors[$i] === 0) {
                    $ext = pathinfo($fileNames[$i], PATHINFO_EXTENSION);
                    $newName = uniqid('', true) . '.' . $ext;
                    $destination = FCPATH . 'uploads/productmedia/' . $newName;
                    if (move_uploaded_file($tmpNames[$i], $destination)) {
                        $sideUploaded[] = $newName;
                    }
                }
            }
        }

        // --- Merge existing side images with new uploads ---
        if (!empty($existingData) && !empty($existingData['pri_File_Name'])) {
            $existingSideImages = json_decode($existingData['pri_File_Name'], true);
            $sideUploaded = array_merge($existingSideImages, $sideUploaded);
        }

        // --- Prepare data array ---
        $imageData = [
            'pr_Id' => $pr_id,
            'pri_Thumbnail' => !empty($thumbnailUploaded) ? $thumbnailUploaded[0] : ($existingData['pri_Thumbnail'] ?? null),
            'pri_File_Name' => !empty($sideUploaded) ? json_encode($sideUploaded) : null,
            'color_details' => json_encode(['color' => $color]),
            'pri_Status' => 1
        ];

        if ($pri_id) {
            // Update existing record
            $this->productimageModel->update($pri_id, $imageData);
        } else {
            // Insert new record
            $imageData['pri_createdon'] = date('Y-m-d H:i:s');
            $imageData['pri_createdby'] = $this->session->get('ad_uid');
            $pri_id = $this->productimageModel->insertProductImages($imageData);
        }

        // --- Handle variants ---
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
                $this->productimageModel->insertVariant($variantData); // Make sure this method handles updates
            }
        }

        $insertedAny = true;
    }

    if ($insertedAny) {
        return $this->response->setJSON([
            'status' => 'success',
            'msg' => 'Product Images Saved Successfully!'
        ]);
    }

    return $this->response->setJSON([
        'status' => 'error',
        'msg' => 'Please Upload At Least One Image for Each Color Group.'
    ]);
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