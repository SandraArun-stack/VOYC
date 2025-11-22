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
        $prname = $this->productimageModel->getProductName($pr_id);

        // Aggregate variants per product image
        $aggregated = [];
        foreach ($data as $row) {
            $id = $row['prv_Id'];

            if (!isset($aggregated[$id])) {
                $aggregated[$id] = [
                    'pri_Id' => $row['pri_Id'],
                    'pr_Id' => $row['pr_id'],
                    'prv_Id' => $row['prv_Id'],
                    'pr_Name' => $row['pr_name'] ?? 'N/A',
                    'colors' => json_decode($row['color_details'], true)['color'] ?? 'N/A',
                    'sizes' => [],
                    'prices' => [],
                    'stocks' => [],
                    'reset_stocks' => [],
                    'pri_Status' => $row['pri_Status'],
                    'prv_Status' => $row['prv_Status'] ?? 1,
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
            if ($row['prv_Status'] == 3) {
                // Deleted → show badge only, no toggle
                $row['status_switch'] = '<span class="badge bg-danger">Deleted</span>';
            } else {
                // Active / Inactive → show toggle
                $row['status_switch'] = '<div class="form-check form-switch">
                    <input class="form-check-input checkactiveimage"
                        type="checkbox"
                        id="checkimg-' . $row['prv_Id'] . '"
                        value="' . $row['prv_Id'] . '" ' . ($row['prv_Status'] == 1 ? 'checked' : '') . '>

                    <label class="form-check-label pl-0 label-check"
                        for="statusSwitch-' . $row['prv_Id'] . '"></label>
                </div>';
            }


            // Action buttons
            $row['actions'] = '
                <a href="' . base_url('admin/product/image/edit/' . $row['pr_Id'] . '/' . $row['pri_Id']) . '">
                    <i class="bi bi-pencil-square"></i>
                </a>&nbsp;
                <i class="bi bi-trash text-danger icon-clickable" style="cursor: pointer;" 
                onclick="confirmDelete(' . $row['prv_Id'] . ')"></i>&nbsp;';
        }
        // $productName = $aggregated ? reset($aggregated)['pr_Name'] : ($data[0]['pr_name'] ?? 'N/A');


        return $this->response->setJSON([
            'draw' => intval($this->request->getPost('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => array_values($aggregated),
            'pr_Name' => $prname
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
        $data['pr_custom'] = $this->productimageModel->checkCustomized($pr_id);


        // Add Mode → only product ID exists
        if ($pr_id !== null && $pri_id === null) {
            $data['mode'] = 'add';
            $data['pr_id'] = $pr_id;
            // $data['pr_custom'] = $pr_custom;
            $data['productimages'] = []; // empty, since we're adding new image
        }
        // Edit Mode → both product ID and image ID exist
        elseif ($pr_id !== null && $pri_id !== null) {
            $data['mode'] = 'edit';
            $data['pr_id'] = $pr_id;
            $data['pri_id'] = $pri_id;
            $data['productimages'] = $this->productimageModel->getProductImages($pri_id);
            $data['variants'] = $this->productimageModel->getVariantsByPriId($pri_id);

            // echo $pri_id; exit();
        }
        // No valid product ID → redirect or show 404
        else {
            return redirect()->to(base_url('admin/product'));
        }

        // Load views
        $template = view('Admin/common/header');
        $template .= view('Admin/common/leftmenu');
        $template .= view('Admin/productimage_add', $data);
        $template .= view('Admin/common/footer');
        $template .= view('Admin/page_scripts/productimagejs');
        return $template;
    }

    public function update($pr_id, $pri_id)
    {
        $colorsData = $this->request->getPost('colors');

        if (empty($colorsData) || empty($pr_id) || empty($pri_id)) {
            return $this->response->setJSON([
                'status' => 'error',
                'msg' => 'Missing Product, Image ID, or Color Data.'
            ]);
        }

        $existingData = $this->productimageModel->find($pri_id);

        foreach ($colorsData as $colorIndex => $colorGroup) {
            $color = trim($colorGroup['color'] ?? '');
            $sizes = $colorGroup['sizes'] ?? [];
            $prices = $colorGroup['prices'] ?? [];
            $stock = $colorGroup['stock'] ?? [];
            $reset_stock = $colorGroup['reset_stock'] ?? [];

            if (empty($color) || empty($sizes)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'msg' => 'Please Provide All Required Data and At Least One Size.'
                ]);
            }

            // --- Upload new files (reuse helper) ---
            $thumbnailUploaded = $this->uploadFiles($_FILES, $colorIndex, 'images', ['jpg', 'jpeg', 'png', 'webp']);
            $sideUploaded = $this->uploadFiles($_FILES, $colorIndex, 'side_image', ['jpg', 'jpeg', 'png', 'webp']);
            $sleeveUploaded = $this->uploadFiles($_FILES, $colorIndex, 'sleev_image', ['jpg', 'jpeg', 'png', 'webp']);
            $RSleeve_Img = $this->uploadFiles($_FILES, $colorIndex, 'RSleeve_Img', ['jpg', 'jpeg', 'png', 'webp']);
            $LSleeve_Img = $this->uploadFiles($_FILES, $colorIndex, 'LSleeve_Img', ['jpg', 'jpeg', 'png', 'webp']);
           $videoUploaded = $this->uploadFiles($_FILES, $colorIndex, 'video', ['mp4','mov','avi','mkv'], true);



            // --- Preserve existing data if nothing new uploaded ---
            if (empty($thumbnailUploaded) && !empty($existingData['pri_Thumbnail'])) {
                $thumbnailUploaded[] = $existingData['pri_Thumbnail'];
            }

            if (empty($sideUploaded) && !empty($existingData['pri_File_Name'])) {
                $sideUploaded = json_decode($existingData['pri_File_Name'], true);
            }

            if (empty($sleeveUploaded) && !empty($existingData['pri_Sleev_Name'])) {
                $sleeveUploaded = json_decode($existingData['pri_Sleev_Name'], true);
            }

            if (empty($RSleeve_Img) && !empty($existingData['RSleeve_Img'])) {
                $RSleeve_Img[] = $existingData['RSleeve_Img'];
            }

            if (empty($LSleeve_Img) && !empty($existingData['LSleeve_Img'])) {
                $LSleeve_Img[] = $existingData['LSleeve_Img'];
            }

            if (!$videoUploaded && !empty($existingData['pri_Video'])) {
                $videoUploaded = $existingData['pri_Video'];  // ✔ string
            }



            // --- Safety check ---
            if (empty($thumbnailUploaded) || empty($sideUploaded)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'msg' => 'Please Upload at Least One Thumbnail and One Side Image For Each Color.'
                ]);
            }

            // --- Update Image Record ---
            $imageData = [
                'pri_Thumbnail' => $thumbnailUploaded[0],
                'pri_File_Name' => json_encode($sideUploaded),
                'pri_Sleev_Name' => !empty($sleeveUploaded) ? json_encode($sleeveUploaded) : $existingData['pri_Sleev_Name'],
                'RSleeve_Img' => $RSleeve_Img[0] ?? $existingData['RSleeve_Img'],
                'LSleeve_Img' => $LSleeve_Img[0] ?? $existingData['LSleeve_Img'],
                'color_details' => json_encode(['color' => $color]),
                'pri_Status' => 1,
                'pri_updatedon' => date('Y-m-d H:i:s'),
                'pri_updatedby' => $this->session->get('ad_uid'),
               'pri_Video' => $videoUploaded ?: $existingData['pri_Video']

            ];

            $this->productimageModel->updateProductimage($pri_id, $imageData);

            // --- Update Variants ---
            foreach ($sizes as $size) {
                $prv_id = isset($colorGroup['prv_id'][$size]) ? intval($colorGroup['prv_id'][$size]) : null;
                $variantData = [
                    'pr_id' => $pr_id,
                    'pri_id' => $pri_id,
                    'prv_Size' => $size,
                    'prv_price' => $prices[$size] ?? 0,
                    'stock' => $stock[$size] ?? 0,
                    'reset_stock' => $reset_stock[$size] ?? 0
                ];

                if (!empty($prv_id)) {
                    $existingVariant = $this->productimageModel->getVariantByPriIdSizeAndPrvId($pri_id, $size, $prv_id);
                    if ($existingVariant) {
                        $this->productimageModel->updateVariant($prv_id, $variantData);
                    } else {
                        $this->productimageModel->insertVariant($variantData);
                    }
                } else {
                    $this->productimageModel->insertVariant($variantData);
                }
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'msg' => 'Product Images Updated Successfully!',
            'redirect' => base_url('admin/product/image/' . $pr_id)
        ]);
    }


    public function save()
    {

        $pri_id = $this->request->getPost('pri_id');
        $pr_id = $this->request->getPost('pr_id');

        if (!empty($pri_id)) {
            return $this->update($pr_id, $pri_id);
        }


        $colorsData = $this->request->getPost('colors');
        if (empty($colorsData) || empty($pr_id)) {
            return $this->response->setJSON([
                'status' => 'error',
                'msg' => 'Missing Product or Color Data.'
            ]);
        }

        $insertedAny = false;
        $product = $this->productimageModel->checkCustomized($pr_id);

        $isCustom = !empty($product) && $product['pr_custom'] == 1;

        foreach ($colorsData as $colorIndex => $colorGroup) {
            $color = trim($colorGroup['color'] ?? '');
            $sizes = $colorGroup['sizes'] ?? [];
            $prices = $colorGroup['prices'] ?? [];
            $stock = $colorGroup['stock'] ?? [];
            $reset_stock = $colorGroup['reset_stock'] ?? [];

            if (empty($color) || empty($sizes)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'msg' => 'Please Provide All Required Data and At Least One Size.'
                ]);
            }

            // --- Upload all file types using helper ---
            $thumbnailUploaded = $this->uploadFiles($_FILES, $colorIndex, 'images', ['jpg', 'jpeg', 'png', 'webp']);
            $sideUploaded = $this->uploadFiles($_FILES, $colorIndex, 'side_image', ['jpg', 'jpeg', 'png', 'webp']);
            $sleeveUploaded = $this->uploadFiles($_FILES, $colorIndex, 'sleev_image', ['jpg', 'jpeg', 'png', 'webp']);
            $RSleeve_Img = $this->uploadFiles($_FILES, $colorIndex, 'RSleeve_Img', ['jpg', 'jpeg', 'png', 'webp']);
            $LSleeve_Img = $this->uploadFiles($_FILES, $colorIndex, 'LSleeve_Img', ['jpg', 'jpeg', 'png', 'webp']);
            $videoUploaded = $this->uploadFiles($_FILES, $colorIndex, 'videos', ['mp4', 'avi', 'mov', 'mkv', 'webm'], true);

            if (empty($thumbnailUploaded)) {
                return $this->response->setJSON(['status' => 'error', 'msg' => 'Please Upload a Front View of the Product.']);
            } elseif (empty($sideUploaded)) {
                return $this->response->setJSON(['status' => 'error', 'msg' => 'Please Upload a Back View of the Product.']);
            }

            if ($isCustom) {
                if (empty($RSleeve_Img)) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'msg' => 'Right Sleeve Image is required for Custom Products.'
                    ]);
                }
                if (empty($LSleeve_Img)) {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'msg' => 'Left Sleeve Image is required for Custom Products.'
                    ]);
                }
            }



            // --- Insert Image Record ---
            $imageData = [
                'pr_Id' => $pr_id,
                'pri_Video' => $videoUploaded[0] ?? null,
                'pri_Thumbnail' => $thumbnailUploaded[0],
                'pri_File_Name' => json_encode($sideUploaded),
                'RSleeve_Img' => $RSleeve_Img[0] ?? null,
                'LSleeve_Img' => $LSleeve_Img[0] ?? null,
                'pri_Sleev_Name' => !empty($sleeveUploaded) ? json_encode($sleeveUploaded) : null,
                'color_details' => json_encode(['color' => $color]),
                'pri_Status' => 1,
                'pri_createdon' => date('Y-m-d H:i:s'),
                'pri_createdby' => $this->session->get('ad_uid')
            ];

            $pri_id = $this->productimageModel->insertProductImages($imageData);

            // --- Insert Variants ---
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

            $insertedAny = true;
        }

        if ($insertedAny) {
            return $this->response->setJSON([
                'status' => 'success',
                'msg' => 'Product Images Saved Successfully!',
                'redirect' => base_url('admin/product/image/' . $pr_id)
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'msg' => 'No product images were saved.'
        ]);
    }
    // private function uploadFiles($filesArray, $colorIndex, $field, $allowedExt, $isVideo = false)
    // {
    //     $uploaded = [];

    //     if (!empty($filesArray['colors']['name'][$colorIndex][$field][0])) {
    //         foreach ($filesArray['colors']['name'][$colorIndex][$field] as $i => $name) {
    //             if ($filesArray['colors']['error'][$colorIndex][$field][$i] === 0) {
    //                 $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    //                 if (!in_array($ext, $allowedExt)) {
    //                     throw new \RuntimeException("Invalid file type for {$field}. Allowed: " . implode(', ', $allowedExt));
    //                 }

    //                 $newName = uniqid('', true) . '.' . $ext;
    //                 $destination = FCPATH . 'uploads/productmedia/' . $newName;

    //                 if (move_uploaded_file($filesArray['colors']['tmp_name'][$colorIndex][$field][$i], $destination)) {
    //                     $uploaded[] = $newName;
    //                 }
    //             }
    //         }
    //     }

    //     return $uploaded;
    // }
    private function uploadFiles($filesArray, $colorIndex, $field, $allowedExt, $isSingleFile = false)
    {
        // Nothing uploaded
        if (empty($filesArray['colors']['name'][$colorIndex][$field])) {
            return $isSingleFile ? null : [];
        }

        $uploaded = [];

        // CASE 1: Single File
        if ($isSingleFile || !is_array($filesArray['colors']['name'][$colorIndex][$field])) {

            $name = $filesArray['colors']['name'][$colorIndex][$field];
            $error = $filesArray['colors']['error'][$colorIndex][$field];
            $tmp = $filesArray['colors']['tmp_name'][$colorIndex][$field];

            if ($error === 0) {
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowedExt)) {
                    throw new \RuntimeException("Invalid file type for {$field}. Allowed: " . implode(', ', $allowedExt));
                }

                $newName = uniqid('', true) . '.' . $ext;
                $dest = FCPATH . 'uploads/productmedia/' . $newName;

                if (move_uploaded_file($tmp, $dest)) {
                    return $newName;   // return string for single file
                }
            }

            return null;
        }

        // CASE 2: Multiple Files
        foreach ($filesArray['colors']['name'][$colorIndex][$field] as $i => $name) {
            if ($filesArray['colors']['error'][$colorIndex][$field][$i] === 0) {

                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowedExt)) {
                    throw new \RuntimeException("Invalid file type for {$field}. Allowed: " . implode(', ', $allowedExt));
                }

                $newName = uniqid('', true) . '.' . $ext;
                $dest = FCPATH . 'uploads/productmedia/' . $newName;

                if (move_uploaded_file($filesArray['colors']['tmp_name'][$colorIndex][$field][$i], $dest)) {
                    $uploaded[] = $newName;
                }
            }
        }

        return $uploaded;
    }


    public function delete($prv_id = null)
    {
        if (!$this->session->get('ad_uid')) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Unauthorized']);
        }

        if ($prv_id === null) {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Invalid ID']);
        }

        $deleted = $this->productimageModel->deleteVariantById($prv_id);

        if ($deleted) {
            return $this->response->setJSON(['status' => 'success', 'msg' => 'Variant deleted successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'msg' => 'Failed to delete variant']);
        }
    }


    public function changeStatus()
    {
        $prvId = $this->request->getPost('prv_Id');
        $newStatus = $this->request->getPost('prv_Status'); // 1 = active, 2 = inactive

        $db = \Config\Database::connect();
        $variantBuilder = $db->table('product_variants');

        $variant = $variantBuilder->where('prv_Id', $prvId)->get()->getRow();

        if (!$variant) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Variant Not Found'
            ]);
        }

        $update = $variantBuilder
            ->where('prv_Id', $prvId)
            ->update(['prv_Status' => $newStatus]);

        if ($update) {
            $priId = $variant->pri_id;

            $activeVariants = $db->table('product_variants')
                ->where('pri_Id', $priId)
                ->where('prv_Status', 1)
                ->countAllResults();

            $imageStatus = ($activeVariants > 0) ? 1 : 2;

            $db->table('product_image')
                ->where('pri_Id', $priId)
                ->update(['pri_Status' => $imageStatus]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Product Variant Status Updated Successfully!',
                'new_status' => $newStatus
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed To Update Product Variant Status'
            ]);
        }
    }


}