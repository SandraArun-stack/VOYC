<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Admin\ProductModel;

class Product extends BaseController
{

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->input = \Config\Services::request();
        $this->productModel = new \App\Models\Admin\ProductModel();
    }

    public function index()
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to(base_url('admin'));
        }

        $allproducts = $this->productModel->getAllProducts();
        $data['product'] = $allproducts;
        $template = view('Admin/common/header');
        $template .= view('Admin/common/leftmenu');
        $template .= view('Admin/product', $data);
        $template .= view('Admin/product_add_modal');
        $template .= view('Admin/product_video_modal');
        $template .= view('Admin/common/footer');
        $template .= view('Admin/page_scripts/productjs');
        return $template;

    }
    //Product Data List ajax // Listing table data
    public function ajaxList()
    {
        $model = new \App\Models\Admin\ProductModel();
        $data = $model->getDatatables();
        $total = $model->countAll();
        $filtered = $model->countFiltered();

        foreach ($data as &$row) {
            // Default fallbacks
            $row['pr_Name'] = $row['pr_Name'] ?? 'N/A';
            $row['pr_Code'] = $row['pr_Code'] ?? 'N/A';
           
            $row['pr_Stock'] = $row['pr_Stock'] ?? 'N/A';



            // Status toggle switch
            $row['status_switch'] = '<div class="form-check form-switch">
			<input class="form-check-input checkactive"
				   type="checkbox"
				   id="statusSwitch-' . $row['pr_Id'] . '"
				   value="' . $row['pr_Id'] . '" ' . ($row['pr_Status'] == 1 ? 'checked' : '') . '>
			<label class="form-check-label pl-0 label-check"
				   for="statusSwitch-' . $row['pr_Id'] . '"></label>
		</div>';

            // Action buttons
           $row['actions'] = '
    <img class="img-size"
        src="' . base_url(ASSET_PATH . 'Admin/assets/images/image_add.ico') . '"
        alt="Image-add"
        onclick="redirectToProductImage(' . $row['pr_Id'] . ')"
        style="cursor: pointer;">&nbsp;
      			 
		<img class="img-size open-video-modal"
			 src="' . base_url(ASSET_PATH . 'Admin/assets/images/video_add.ico') . '"
			 alt="Video-add"
			 data-toggle="modal"
			 data-target="#videoModal"
			 data-product-id="' . $row['pr_Id'] . '"
			 data-product-name="' . htmlspecialchars($row['pr_Name'], ENT_QUOTES) . '"
			 onclick="openvideoModal(' . $row['pr_Id'] . ', \'' . addslashes($row['pr_Name']) . '\')"
			 style="cursor: pointer;">     
             
		<a href="' . base_url('admin/product/edit/' . $row['pr_Id']) . '">
			<i class="bi bi-pencil-square"></i>
		</a>&nbsp;

        
        <i class="bi bi-trash text-danger icon-clickable" style="cursor: pointer;" 
		   onclick="confirmDelete(' . $row['pr_Id'] . ')"></i>&nbsp;';

        }

        return $this->response->setJSON([
            'draw' => intval($this->request->getPost('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data
        ]);
    }


    //Product Add
    public function addProduct($pr_id = null)
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to(base_url('/admin/'));
        }

        $data = [];
        $data['categories'] = $this->productModel->getAllCategories();

        if ($pr_id) {
            $product = $this->productModel->getProductByid($pr_id);



            if (!$pr_id) {
                return redirect()->to('admin/product')->with('error', 'product not found');
            }

            $data['product'] = (array) $product;
        }

        $template = view('Admin/common/header');
        $template .= view('Admin/common/leftmenu');
        $template .= view('Admin/product_add', $data);
        $template .= view('Admin/common/footer');
        $template .= view('Admin/page_scripts/productjs');
        return $template;
    }

    public function getSubcategories()
    {
        $cat_id = $this->request->getPost('cat_id');
        $subcategories = $this->productModel->getSubcategoriesByCatId($cat_id);
        return $this->response->setJSON($subcategories);
    }


    //Product save

    // Product save
    public function saveProduct()
    {
       
        $pr_id = $this->input->getPost('pr_id');
        $sub_id = $this->input->getPost('sub_id');
        $cat_id = $this->input->getPost('cat_id');
        $product_name = trim($this->input->getPost('product_name'));
        $product_name = ucwords(strtolower(trim($product_name)));
        $product_code = trim($this->request->getPost('product_code')); // ✅ CodeIgniter 4

        $product_description = $this->input->getPost('product_description');

        $product_description = preg_replace_callback('/([.!?]\s*)([a-z])/',
                                            fn($m) => $m[1] . strtoupper($m[2]), 
                                            ucfirst(strtolower(trim($product_description))));


        $product_stock = $this->input->getPost('product_stock');
        $reset_stock = $this->input->getPost('reset_stock');
        $sleeve_style   = ucwords(strtolower(trim($this->request->getPost('sleeve_style'))));
        $fabric   = ucwords(strtolower(trim($this->request->getPost('fabric'))));
        $stitching   = ucwords(strtolower(trim($this->request->getPost('stitching'))));
        
        $DisCountFrom = 0;     
        
        if (empty($cat_id) || empty($product_name) || empty($product_code) || empty($product_stock)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'All Required Fields Must Be Filled.'
            ]);
        }
        if (!preg_match('/^[a-zA-Z0-9 _-]+$/', $product_name)) {
    return $this->response->setJSON([
        'status' => 'error',
        'field' => 'product_name',
        'message' => 'Invalid Product Name.'
    ]);
}
if (!preg_match('/^[a-zA-Z0-9 _\-()\/\\\\]+$/', $product_code)) {
    return $this->response->setJSON([
        'status' => 'error',
        'field' => 'product_code',
        'message' => 'Invalid Product Code'
    ]);
}
if(!empty($product_description)) {
if (!preg_match('/^[a-zA-Z0-9\s.,;:()\'"\/&_@+-]+$/', $product_description)) {
    return $this->response->setJSON([
        'status' => 'error',
        'field' => 'product_description',
        'message' => 'Description contains invalid characters. Allowed: letters, numbers, spaces, and basic punctuation.'
    ]);
}
}
if (!ctype_digit($product_stock)) {
    return $this->response->setJSON([
        'status' => 'error',
        'field' => 'product_stock',
        'message' => 'Product Stock Must be a Number.'
    ]);
}

// Validate reset_stock
if (!ctype_digit($reset_stock)) {
    return $this->response->setJSON([
        'status' => 'error',
        'field' => 'reset_stock',
        'message' => 'Reset Stock Must be a Number.'
    ]);
}

$allowedPattern = '/^[a-zA-Z0-9\s\-\&\/()]+$/';
if(!empty($sleeve_style)){
if (!preg_match($allowedPattern, $sleeve_style)) {
    return $this->response->setJSON([
        'status' => 'error',
        'field' => 'sleeve_style',
        'message' => 'Invalid Sleeve Style.'
    ]);
}
}
if(!empty($fabric)){
if (!preg_match($allowedPattern, $fabric)) {
    return $this->response->setJSON([
        'status' => 'error',
        'field' => 'fabric',
        'message' => 'Invalid Fabric Name.'
    ]);
}
}
if(!empty($stitching)){

if (!preg_match($allowedPattern, $stitching)) {
    return $this->response->setJSON([
        'status' => 'error',
        'field' => 'stitching',
        'message' => 'Invalid Stitching Style.'
    ]);
}
    }
        // Check if product name already exists (excluding current ID)
        if ($this->productModel->isProductExists($product_name, $pr_id)) {
            return $this->response->setJSON([
                'status' => 'error',
                'field' => 'product_name',
                'message' => 'Product Name Already Exists.'
            ]);
        }

        // Check if product code already exists (excluding current ID)
        if ($this->productModel->isProductCodeExists($product_code, $pr_id)) {
            return $this->response->setJSON([
                'status' => 'error',
                'field' => 'pr_Code',
                'message' => 'Product Code Already Exists.'
            ]);
        }



        $data = [
            'pr_Name' => $product_name,
            'pr_Code' => $product_code,
            'pr_Description' => $product_description,
            'cat_Id' => $cat_id,
            'sub_Id' => $sub_id,
            'pr_Stock' => $product_stock,
            'pr_Reset_Stock' => $reset_stock,
            'pr_Sleeve_Style' => $sleeve_style,
            'pr_Fabric' => $fabric,
            'pr_Stitch_Type' => $stitching,
            'pr_modifyby' => $this->session->get('ad_uid'),
            'pr_modifyon' => date("Y-m-d H:i:s"),
            'discount_from' => $DisCountFrom,
        ];

        if (empty($pr_id)) {
            $data['pr_Status'] = 1; 
            $data['pr_createdon'] = date("Y-m-d H:i:s");
            $data['pr_createdby'] = $this->session->get('ad_uid');

            if ($this->productModel->productInsert($data)) {

                return $this->response->setJSON([
                    'status' => 1,
                    'msg' => 'Product Created Successfully.',
                    'redirect' => base_url('admin/product')
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'msg' => 'Product not created.',
                ]);
            }
        } else {
            if ($this->productModel->updateProduct($pr_id, $data)) {

                return $this->response->setJSON([
                    'status' => 1,
                    'msg' => 'Product Updated Successfully.',
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 0,
                    'msg' => 'Product Not Updated Successfully.',
                ]);
            }
        }
    }



    //Media upload
    public function uploadMedia()
    {
        $productId = $this->request->getPost('product_id');
        $files = $this->request->getFileMultiple('files'); // Corrected

        $newFileNames = [];

        if ($files) {
            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $name = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/productmedia/', $name);
                    $newFileNames[] = $name;
                }
            }

            $productModel = new \App\Models\Admin\ProductModel();

            $existingMediaJson = $productModel->getProductImages($productId);


            $existingMedia = json_decode($existingMediaJson, true);

            $allNames = [];

            if (!empty($existingMedia) && isset($existingMedia[0]['name'])) {
                $allNames = $existingMedia[0]['name'];
            }

            $allNames = array_merge($allNames, $newFileNames);

            $updatedJson = [
                ['name' => $allNames]
            ];

            $productModel->updateProductImages($productId, json_encode($updatedJson));

            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false]);
    }

    //get product images
    public function getProductImages($productId)
    {
        $productModel = new \App\Models\Admin\ProductModel();
        $imagesJson = $productModel->getProductImages($productId);
        $images = json_decode($imagesJson, true);

        // Extract image names
        $imageList = $images[0]['name'] ?? [];

        return $this->response->setJSON($imageList);
    }

    //Delte the whole product
    public function deleteProduct($pr_id)
    {
        if ($pr_id) {
            $modified_by = $this->session->get('ad_uid');
            $pr_status = $this->productModel->deleteProductById(3, $pr_id, $modified_by);
            if ($pr_status) {
                echo json_encode([
                    'success' => true,
                    'msg' => 'Product Deleted Successfully.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'msg' => 'Failed To Delete Product.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'msg' => 'Invalid Request.'
            ]);
        }
    }

    //Delete the single product


    public function deleteProductImage()
    {
        $request = $this->request->getJSON(true);

        $productId = $request['product_id'] ?? null;
        $image = $request['image'] ?? null;

        if (!$productId || !$image) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing Product id Or Image.']);
        }

        // Delete from folder
        $imagePath = FCPATH . 'uploads/productmedia/' . $image;

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete from database
        $productModel = new \App\Models\Admin\ProductModel();
        $deleted = $productModel->delete_image($productId, $image);

        return $this->response->setJSON([
            'success' => $deleted,
            'message' => $deleted ? 'Image deleted successfully.' : 'Image Not Deleted From DB.'
        ]);
    }


    public function ProductuploadVideo()
    {
        $productId = $this->request->getPost('product_id');
        $videoFile = $this->request->getFile('video');

        // Check if video file is uploaded, valid, and not moved
        if ($videoFile && $videoFile->isValid() && !$videoFile->hasMoved()) {

            // Check file size (max 4MB = 4 * 1024 * 1024 = 4194304 bytes)
            if ($videoFile->getSize() > 10485760) { // 10 * 1024 * 1024
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Your video size is too large. Please upload a video within 10MB.'
                ]);
            }

            // Check MIME type (allow only video formats)
            $allowedMimeTypes = ['video/mp4', 'video/avi', 'video/mpeg', 'video/quicktime', 'video/x-matroska'];
            if (!in_array($videoFile->getMimeType(), $allowedMimeTypes)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Only video files are allowed. Please upload a valid video format.'
                ]);
            }

            // Proceed with storing the file
            $newName = $videoFile->getRandomName();
            $videoFile->move('uploads/productmedia', $newName);

            $productModel = new \App\Models\Admin\ProductModel();
            $productModel->updateProductVideo($productId, $newName);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Video Uploaded Successfully.',
                'video' => $newName
            ]);
        } else {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Invalid Video File.'
            ]);
        }
    }

    public function deleteVideo()
    {
        $request = service('request');
        $productId = $request->getPost('product_id');
        $videoName = $request->getPost('video_name');

        if (!$productId || !$videoName) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Missing product_id or video_name'
            ]);
        }

        // Build the full path
        $filePath = FCPATH . 'uploads/productmedia/' . $videoName;

        if (file_exists($filePath)) {
            if (!unlink($filePath)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed To Delete File. Check Permissions.'
                ]);
            }
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'File Not Found On Server.'
            ]);
        }

        // Update DB to null the video field
        $productModel = new \App\Models\Admin\ProductModel();
        $updateResult = $productModel->deleteProductVideo($productId);

        if ($updateResult) {
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed To Update Database.'
            ]);
        }
    }


    public function getVideo()
    {
        $request = service('request');
        $productId = $request->getPost('product_id');

        $productModel = new \App\Models\Admin\ProductModel();
        $product = $productModel->getVideo($productId);

        if ($product && $product->product_video) {
            return $this->response->setJSON([
                'status' => 'success',
                'video' => $product->product_video
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'No Video Found']);
    }

    //ChangeStatus

    public function changeStatus()
    {
        $prId = $this->request->getPost('pr_Id');
        $newStatus = $this->request->getPost('pr_Status');

        $productModel = new \App\Models\Admin\ProductModel();
        $product = $productModel->getProductByid($prId);

        if (!$product) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Product Not Found'
            ]);
        }

        $update = $productModel->updateProduct($prId, ['pr_Status' => $newStatus]);

        if ($update) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Product Status Updated Successfully!',
                'new_status' => $newStatus
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed To Update Status'
            ]);
        }
    }

    //View Product

    public function viewProduct($id)
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to(base_url('admin'));
        }

        $product = $this->productModel->getProductByid($id);
        $data['product'] = $product;
        // print_r($data['product']);
        // exit;

        $template = view('Admin/common/header');
        $template .= view('Admin/common/leftmenu');
        $template .= view('Admin/product_view', $data);
        $template .= view('Admin/common/footer');

        return $template;

    }

}