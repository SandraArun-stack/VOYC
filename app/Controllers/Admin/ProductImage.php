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
    <a href="' . base_url('admin/product/image/edit/' . $row['pr_Id'] . '/' . $row['pri_Id']) . '">
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
            // echo "adding"; exit();
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



    // public function update($pr_id, $pri_id)
    // {
    //     $colorsData = $this->request->getPost('colors');

    //     if (empty($colorsData) || empty($pr_id) || empty($pri_id)) {
    //         return $this->response->setJSON([
    //             'status' => 'error',
    //             'msg' => 'Missing Product, Image ID, or Color Data.'
    //         ]);
    //     }

    //     foreach ($colorsData as $colorIndex => $colorGroup) {
    //         $color = trim($colorGroup['color'] ?? '');
    //         $sizes = $colorGroup['sizes'] ?? [];
    //         // print_r($sizes) ;exit();
    //         $prices = $colorGroup['prices'] ?? [];
    //         $stock = $colorGroup['stock'] ?? [];
    //         $reset_stock = $colorGroup['reset_stock'] ?? [];

    //         if (empty($color) || empty($sizes)) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'msg' => 'Please Provide All Required Data and At Least One Size.'
    //             ]);
    //         }

    //         // Retrieve the existing image data
    //         $existingData = $this->productimageModel->find($pri_id);

    //         // Default values for image handling (keep existing if no new uploads)
    //         $thumbnailUploaded = [$existingData['pri_Thumbnail']];
    //         $sideUploaded = !empty($existingData['pri_File_Name']) ? json_decode($existingData['pri_File_Name'], true) : [];

    //         // If new images are uploaded, process them
    //         if (!empty($_FILES['colors']['name'][$colorIndex]['images'][0])) {
    //             $thumbnailUploaded = [];
    //             $fileNames = $_FILES['colors']['name'][$colorIndex]['images'];
    //             $tmpNames = $_FILES['colors']['tmp_name'][$colorIndex]['images'];
    //             $errors = $_FILES['colors']['error'][$colorIndex]['images'];

    //             foreach ($errors as $i => $err) {
    //                 if ($err === 0) {
    //                     $ext = strtolower(pathinfo($fileNames[$i], PATHINFO_EXTENSION));
    //                     if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
    //                         return $this->response->setJSON([
    //                             'status' => 'error',
    //                             'msg' => 'Only JPG, PNG, or WEBP images are allowed.'
    //                         ]);
    //                     }
    //                     $newName = uniqid('', true) . '.' . $ext;
    //                     $destination = FCPATH . 'uploads/productmedia/' . $newName;
    //                     if (move_uploaded_file($tmpNames[$i], $destination)) {
    //                         $thumbnailUploaded[] = $newName; // New thumbnail image uploaded
    //                     }
    //                 }
    //             }
    //         }

    //         if (!empty($_FILES['colors']['name'][$colorIndex]['side_image'][0])) {
    //             $sideUploaded = [];
    //             $fileNames = $_FILES['colors']['name'][$colorIndex]['side_image'];
    //             $tmpNames = $_FILES['colors']['tmp_name'][$colorIndex]['side_image'];
    //             $errors = $_FILES['colors']['error'][$colorIndex]['side_image'];

    //             foreach ($errors as $i => $err) {
    //                 if ($err === 0) {
    //                     $ext = strtolower(pathinfo($fileNames[$i], PATHINFO_EXTENSION));
    //                     if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
    //                         return $this->response->setJSON([
    //                             'status' => 'error',
    //                             'msg' => 'Only JPG, PNG, or WEBP images are Allowed.'
    //                         ]);
    //                     }
    //                     $newName = uniqid('', true) . '.' . $ext;
    //                     $destination = FCPATH . 'uploads/productmedia/' . $newName;
    //                     if (move_uploaded_file($tmpNames[$i], $destination)) {
    //                         $sideUploaded[] = $newName; // New side image uploaded
    //                     }
    //                 }
    //             }
    //         }

    //         // Handle Sleeve Images upload
    //             $sleeveUploaded = !empty($existingData['pri_Sleev_Name']) ? json_decode($existingData['pri_Sleev_Name'], true) : [];

    //             if (!empty($_FILES['colors']['name'][$colorIndex]['sleev_image'][0])) {
    //                 $sleeveUploaded = [];
    //                 $fileNames = $_FILES['colors']['name'][$colorIndex]['sleev_image'];
    //                 $tmpNames = $_FILES['colors']['tmp_name'][$colorIndex]['sleev_image'];
    //                 $errors = $_FILES['colors']['error'][$colorIndex]['sleev_image'];

    //                 foreach ($errors as $i => $err) {
    //                     if ($err === 0) {
    //                         $ext = strtolower(pathinfo($fileNames[$i], PATHINFO_EXTENSION));
    //                         if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
    //                             return $this->response->setJSON([
    //                                 'status'=>'error',
    //                                 'msg'=>'Only JPG, PNG, or WEBP images are allowed for Sleeve Images.'
    //                             ]);
    //                         }
    //                         $newName = uniqid('', true) . '.' . $ext;
    //                         $destination = FCPATH . 'uploads/productmedia/' . $newName;
    //                         if (move_uploaded_file($tmpNames[$i], $destination)) {
    //                             $sleeveUploaded[] = $newName;
    //                         }
    //                     }
    //                 }
    //             }

    //             // Update imageData with sleeve images
    //             $imageData['pri_Sleev_Name'] = !empty($sleeveUploaded) ? json_encode($sleeveUploaded) : $existingData['pri_Sleev_Name'];


    //         // Ensure there is at least one new thumbnail and one side image
    //         if (empty($thumbnailUploaded) || empty($sideUploaded)) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'msg' => 'Please Upload at Least One Thumbnail and One Side Image For Each Color.'
    //             ]);
    //         }

    //         // Prepare image data for update
    //         $imageData = [
    //             'pri_Thumbnail' => $thumbnailUploaded[0],
    //             'pri_File_Name' => json_encode($sideUploaded),
    //             'color_details' => json_encode(['color' => $color]),
    //             'pri_Status' => 1
    //         ];

    //         // Update image record in the database
    //         $this->productimageModel->updateProductimage($pri_id, $imageData);

    //         // Update variants for each size
    //         foreach ($sizes as $size) {
    //             $prv_id = isset($colorGroup['prv_id'][$size]) ? intval($colorGroup['prv_id'][$size]) : null;
    //             // echo $prv_id;exit();
    //             $variantData = [
    //                 'pr_id' => $pr_id,
    //                 'pri_id' => $pri_id,
    //                 'prv_Size' => $size,
    //                 'prv_price' => $prices[$size],
    //                 'stock' => $stock[$size],
    //                 'reset_stock' => $reset_stock[$size] ?? 0
    //             ];

    //             if (!empty($prv_id)) {
    //                     // echo"yes"; exit();

    //                 // Check if variant exists and update or insert
    //                 $existingVariant = $this->productimageModel->getVariantByPriIdSizeAndPrvId($pri_id, $size, $prv_id);

    //                 if ($existingVariant) {
    //                     // echo"yes"; exit();
    //                     // Update if a matching variant exists
    //                     $this->productimageModel->updateVariant($prv_id, $variantData);
    //                 } else {
    //                     // Insert if no matching variant exists
    //                     $this->productimageModel->insertVariant($variantData);
    //                 }
    //             } else {
    //                 // If prv_id is missing, insert as a new variant
    //                 $this->productimageModel->insertVariant($variantData);
    //             }
    //         }
    //     }

    //     return $this->response->setJSON([
    //         'status' => 'success',
    //         'msg' => 'Product Images Updated Successfully!',
    //         'redirect' => base_url('admin/product/image/' . $pr_id)
    //     ]);
    // }



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

        // --- Handle Images ---
        // Thumbnail
        $thumbnailUploaded = [$existingData['pri_Thumbnail']];
        if (!empty($_FILES['colors']['name'][$colorIndex]['images'][0])) {
            $thumbnailUploaded = [];
            foreach ($_FILES['colors']['name'][$colorIndex]['images'] as $i => $name) {
                if ($_FILES['colors']['error'][$colorIndex]['images'][$i] === 0) {
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
                        return $this->response->setJSON(['status'=>'error','msg'=>'Only JPG, PNG, or WEBP images are allowed.']);
                    }
                    $newName = uniqid('', true) . '.' . $ext;
                    $destination = FCPATH.'uploads/productmedia/'.$newName;
                    if (move_uploaded_file($_FILES['colors']['tmp_name'][$colorIndex]['images'][$i], $destination)) {
                        $thumbnailUploaded[] = $newName;
                    }
                }
            }
        }

        // Side Images
        $sideUploaded = !empty($existingData['pri_File_Name']) ? json_decode($existingData['pri_File_Name'], true) : [];
        if (!empty($_FILES['colors']['name'][$colorIndex]['side_image'][0])) {
            $sideUploaded = [];
            foreach ($_FILES['colors']['name'][$colorIndex]['side_image'] as $i => $name) {
                if ($_FILES['colors']['error'][$colorIndex]['side_image'][$i] === 0) {
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
                        return $this->response->setJSON(['status'=>'error','msg'=>'Only JPG, PNG, or WEBP images are allowed.']);
                    }
                    $newName = uniqid('', true) . '.' . $ext;
                    $destination = FCPATH.'uploads/productmedia/'.$newName;
                    if (move_uploaded_file($_FILES['colors']['tmp_name'][$colorIndex]['side_image'][$i], $destination)) {
                        $sideUploaded[] = $newName;
                    }
                }
            }
        }

        // Sleeve Images
        $sleeveUploaded = !empty($existingData['pri_Sleev_Name']) ? json_decode($existingData['pri_Sleev_Name'], true) : [];
        if (!empty($_FILES['colors']['name'][$colorIndex]['sleev_image'][0])) {
            $sleeveUploaded = [];
            foreach ($_FILES['colors']['name'][$colorIndex]['sleev_image'] as $i => $name) {
                if ($_FILES['colors']['error'][$colorIndex]['sleev_image'][$i] === 0) {
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
                        return $this->response->setJSON(['status'=>'error','msg'=>'Only JPG, PNG, or WEBP images are allowed for Sleeve Images.']);
                    }
                    $newName = uniqid('', true) . '.' . $ext;
                    $destination = FCPATH.'uploads/productmedia/'.$newName;
                    if (move_uploaded_file($_FILES['colors']['tmp_name'][$colorIndex]['sleev_image'][$i], $destination)) {
                        $sleeveUploaded[] = $newName;
                    }
                }
            }
        }

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
            'color_details' => json_encode(['color' => $color]),
            'pri_Status' => 1
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


    // public function save()
    // {

    //     $pri_id = $this->request->getPost('pri_id');
    //     $pr_id = $this->request->getPost('pr_id');

    //     if (!empty($pri_id)) {
    //         // echo"hai";exit();
    //         return $this->update($pr_id, $pri_id);
    //     }

    //     $colorsData = $this->request->getPost('colors');

    //     if (empty($colorsData) || empty($pr_id)) {
    //         return $this->response->setJSON([
    //             'status' => 'error',
    //             'msg' => 'Missing Product or Color Data.'
    //         ]);
    //     }

    //     $insertedAny = false;

    //     foreach ($colorsData as $colorIndex => $colorGroup) {
    //         $color = trim($colorGroup['color'] ?? '');
    //         $sizes = $colorGroup['sizes'] ?? [];
    //         $prices = $colorGroup['prices'] ?? [];
    //         $stock = $colorGroup['stock'] ?? [];
    //         $reset_stock = $colorGroup['reset_stock'] ?? [];

    //         // ✅ Basic validation
    //         if (empty($color)) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'msg' => 'Please Provide All Required Data.'
    //             ]);
    //         }

    //         if (empty($sizes)) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'msg' => 'Please Select at Least One Size for Each Color.'
    //             ]);
    //         }

    //         // ✅ Validate uploaded files
    //         $thumbnailUploaded = [];
    //         $sideUploaded = [];

    //         // Handle Thumbnail upload
    //         if (!empty($_FILES['colors']['name'][$colorIndex]['images'][0])) {
    //             $fileNames = $_FILES['colors']['name'][$colorIndex]['images'];
    //             $tmpNames = $_FILES['colors']['tmp_name'][$colorIndex]['images'];
    //             $errors = $_FILES['colors']['error'][$colorIndex]['images'];

    //             foreach ($errors as $i => $err) {
    //                 if ($err === 0) {
    //                     $ext = strtolower(pathinfo($fileNames[$i], PATHINFO_EXTENSION));
    //                     if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
    //                         return $this->response->setJSON([
    //                             'status' => 'error',
    //                             'msg' => 'Only JPG, PNG, or WEBP images are allowed.'
    //                         ]);
    //                     }
    //                     $newName = uniqid('', true) . '.' . $ext;
    //                     $destination = FCPATH . 'uploads/productmedia/' . $newName;
    //                     if (move_uploaded_file($tmpNames[$i], $destination)) {
    //                         $thumbnailUploaded[] = $newName;
    //                     }
    //                 }
    //             }
    //         }

    //         // Handle Side Images upload
    //         if (!empty($_FILES['colors']['name'][$colorIndex]['side_image'][0])) {
    //             $fileNames = $_FILES['colors']['name'][$colorIndex]['side_image'];
    //             $tmpNames = $_FILES['colors']['tmp_name'][$colorIndex]['side_image'];
    //             $errors = $_FILES['colors']['error'][$colorIndex]['side_image'];

    //             foreach ($errors as $i => $err) {
    //                 if ($err === 0) {
    //                     $ext = strtolower(pathinfo($fileNames[$i], PATHINFO_EXTENSION));
    //                     if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
    //                         return $this->response->setJSON([
    //                             'status' => 'error',
    //                             'msg' => 'Only JPG, PNG, or WEBP Images are Allowed.'
    //                         ]);
    //                     }
    //                     $newName = uniqid('', true) . '.' . $ext;
    //                     $destination = FCPATH . 'uploads/productmedia/' . $newName;
    //                     if (move_uploaded_file($tmpNames[$i], $destination)) {
    //                         $sideUploaded[] = $newName;
    //                     }
    //                 }
    //             }
    //         }

    //         // Handle Sleeve Images upload
    //             $sleeveUploaded = [];

    //             if (!empty($_FILES['colors']['name'][$colorIndex]['sleev_image'][0])) {
    //                 $fileNames = $_FILES['colors']['name'][$colorIndex]['sleev_image'];
    //                 $tmpNames = $_FILES['colors']['tmp_name'][$colorIndex]['sleev_image'];
    //                 $errors = $_FILES['colors']['error'][$colorIndex]['sleev_image'];

    //                 foreach ($errors as $i => $err) {
    //                     if ($err === 0) {
    //                         $ext = strtolower(pathinfo($fileNames[$i], PATHINFO_EXTENSION));
    //                         if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
    //                             return $this->response->setJSON([
    //                                 'status'=>'error',
    //                                 'msg'=>'Only JPG, PNG, or WEBP images are allowed for Sleeve Images.'
    //                             ]);
    //                         }
    //                         $newName = uniqid('', true) . '.' . $ext;
    //                         $destination = FCPATH . 'uploads/productmedia/' . $newName;
    //                         if (move_uploaded_file($tmpNames[$i], $destination)) {
    //                             $sleeveUploaded[] = $newName;
    //                         }
    //                     }
    //                 }
    //             }

    //             // Add sleeve images to imageData before insert
    //             $imageData['pri_Sleev_Name'] = !empty($sleeveUploaded) ? json_encode($sleeveUploaded) : null;


    //         // Require at least one image
    //         if (empty($thumbnailUploaded) || empty($sideUploaded)) {
    //             return $this->response->setJSON([
    //                 'status' => 'error',
    //                 'msg' => 'Please Upload at Least One Thumbnail and One Side Image For Each Color.'
    //             ]);
    //         }

    //         // ✅ Validate price & stock for each size
    //         foreach ($sizes as $size) {
    //             $price = $prices[$size] ?? 0;
    //             $stk = $stock[$size] ?? 0;
    //             if ($price <= 0) {
    //                 return $this->response->setJSON([
    //                     'status' => 'error',
    //                     'msg' => "Invalid Price for Size $size in Color $color."
    //                 ]);
    //             }
    //             if ($stk < 0) {
    //                 return $this->response->setJSON([
    //                     'status' => 'error',
    //                     'msg' => "Invalid Stock for Size $size in Color $color."
    //                 ]);
    //             }
    //         }

    //         // ✅ Prepare data for saving
    //         $imageData = [
    //             'pr_Id' => $pr_id,
    //             'pri_Thumbnail' => $thumbnailUploaded[0],
    //             'pri_File_Name' => json_encode($sideUploaded),
    //             'color_details' => json_encode(['color' => $color]),
    //             'pri_Status' => 1,
    //             'pri_createdon' => date('Y-m-d H:i:s'),
    //             'pri_createdby' => $this->session->get('ad_uid')
    //         ];

    //         // Save image record
    //         $pri_id = $this->productimageModel->insertProductImages($imageData);

    //         // ✅ Save variants
    //         foreach ($sizes as $size) {
    //             $variantData = [
    //                 'pr_id' => $pr_id,
    //                 'pri_id' => $pri_id,
    //                 'prv_Size' => $size,
    //                 'prv_price' => $prices[$size],
    //                 'stock' => $stock[$size],
    //                 'reset_stock' => $reset_stock[$size] ?? 0
    //             ];
    //             $this->productimageModel->insertVariant($variantData);
    //         }

    //         $insertedAny = true;
    //     }

    //     if ($insertedAny) {
    //         return $this->response->setJSON([
    //             'status' => 'success',
    //             'msg' => 'Product Images Saved Successfully!',
    //             'redirect' => base_url('admin/product/image/' . $pr_id)
    //         ]);
    //     }

    //     return $this->response->setJSON([
    //         'status' => 'error',
    //         'msg' => 'No product images were saved.'
    //     ]);
    // }


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

        // --- Handle Images ---
        $thumbnailUploaded = [];
        $sideUploaded = [];
        $sleeveUploaded = [];

        // Thumbnail
        if (!empty($_FILES['colors']['name'][$colorIndex]['images'][0])) {
            foreach ($_FILES['colors']['name'][$colorIndex]['images'] as $i => $name) {
                if ($_FILES['colors']['error'][$colorIndex]['images'][$i] === 0) {
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
                        return $this->response->setJSON(['status'=>'error','msg'=>'Only JPG, PNG, or WEBP images are allowed.']);
                    }
                    $newName = uniqid('', true) . '.' . $ext;
                    $destination = FCPATH.'uploads/productmedia/'.$newName;
                    if (move_uploaded_file($_FILES['colors']['tmp_name'][$colorIndex]['images'][$i], $destination)) {
                        $thumbnailUploaded[] = $newName;
                    }
                }
            }
        }

        // Side Images
        if (!empty($_FILES['colors']['name'][$colorIndex]['side_image'][0])) {
            foreach ($_FILES['colors']['name'][$colorIndex]['side_image'] as $i => $name) {
                if ($_FILES['colors']['error'][$colorIndex]['side_image'][$i] === 0) {
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
                        return $this->response->setJSON(['status'=>'error','msg'=>'Only JPG, PNG, or WEBP images are allowed.']);
                    }
                    $newName = uniqid('', true) . '.' . $ext;
                    $destination = FCPATH.'uploads/productmedia/'.$newName;
                    if (move_uploaded_file($_FILES['colors']['tmp_name'][$colorIndex]['side_image'][$i], $destination)) {
                        $sideUploaded[] = $newName;
                    }
                }
            }
        }

        // Sleeve Images
        if (!empty($_FILES['colors']['name'][$colorIndex]['sleev_image'][0])) {
            foreach ($_FILES['colors']['name'][$colorIndex]['sleev_image'] as $i => $name) {
                if ($_FILES['colors']['error'][$colorIndex]['sleev_image'][$i] === 0) {
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
                        return $this->response->setJSON(['status'=>'error','msg'=>'Only JPG, PNG, or WEBP images are allowed for Sleeve Images.']);
                    }
                    $newName = uniqid('', true) . '.' . $ext;
                    $destination = FCPATH.'uploads/productmedia/'.$newName;
                    if (move_uploaded_file($_FILES['colors']['tmp_name'][$colorIndex]['sleev_image'][$i], $destination)) {
                        $sleeveUploaded[] = $newName;
                    }
                }
            }
        }

       if (empty($thumbnailUploaded) || empty($sideUploaded) || empty($sleeveUploaded)) {
            return $this->response->setJSON([
                'status' => 'error',
                'msg' => 'Please Upload at Least One Thumbnail, One Side Image, and One Sleeve Image For Each Color.'
            ]);
        }

        // --- Insert Image Record ---
        $imageData = [
            'pr_Id' => $pr_id,
            'pri_Thumbnail' => $thumbnailUploaded[0],
            'pri_File_Name' => json_encode($sideUploaded),
            'pri_Sleev_Name' => !empty($sleeveUploaded) ? json_encode($sleeveUploaded) : null,
            'color_details' => json_encode(['color'=>$color]),
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