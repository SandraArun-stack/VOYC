<?php
namespace App\Controllers;

use App\Models\tshirtModel;
use App\Models\CartModel;
use CodeIgniter\Controller;

class Tshirt extends Controller
{
    protected $session;
    protected $request;
    protected $ShopModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->tshirtModel = new tshirtModel();
        $this->CartModel = new CartModel();
    }

    public function index($prId = null, $priId = null)
    {
        $session = session();
        $userId = $session->get('user_id');
        $cartCount = $this->CartModel->getCartItemCount($userId);

        if (!empty($prId) && !empty($priId)) {
            $cust_image = $this->tshirtModel->get_Image($prId, $priId);
            $allData = $this->tshirtModel->get_Data_For_Pr_Id($prId);
            $customisationPrice = $this->tshirtModel->get_customisation_price();
            if (!$cust_image) {
                return redirect()->to(base_url());
            } else {
                // Pass all necessary data, including images
                $data = [
                    'prId' => $prId,
                    'priId' => $priId,
                    'cust_image' => $cust_image,
                    'allData' => $allData,
                    'customisationPrice' => $customisationPrice
                ];
                return view('common/header', ['cartCount' => $cartCount])
                    . view('tshirt', $data)
                    . view('common/footer')
                    . view('pagescripts/tshirtjs');
            }


        } else {
            $customisationPrice = $this->tshirtModel->get_customisation_price();
            $data = [
                'customisationPrice' => $customisationPrice
            ];

            return view('common/header', ['cartCount' => $cartCount])
                . view('tshirt')
                . view('common/footer')
                . view('pagescripts/tshirtjs');
        }
    }


    public function saveDesign()
    {
        $userId = $this->session->get('user_id');

        if (!$userId) {
            return $this->response->setJSON([
                'status' => 'login_required'
            ]);
        }


        $designsJson = $this->request->getPost('designs');
        $designs = json_decode($designsJson, true);

        if (!is_array($designs)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid design data'
            ]);
        }

        // Extract views (your new optimized JS sends these keys)
        $frontImageData = $designs['front'] ?? null;
        $backImageData = $designs['back'] ?? null;
        $RsleeveImageData = $designs['RSleeve_Img'] ?? null;
        $LsleeveImageData = $designs['LSleeve_Img'] ?? null;

        $quantity = $this->request->getPost('quantity');
        $totalPrice = $this->request->getPost('totalPrice');
        $selectedSize = $this->request->getPost('selectedSize');
        $uploadedImagesJson = $this->request->getPost('uploadedImages');


        $prId = $this->request->getPost('prId');
        $priId = $this->request->getPost('priId');


        // echo $prvId;exit();
        if (!$frontImageData && !$backImageData && !$RsleeveImageData && !$LsleeveImageData) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No image received'
            ]);
        }


        $uploadDir = FCPATH . 'uploads/designs/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // ✅ Use the private helper method
        $frontFileName = $this->saveBase64Image($frontImageData, $uploadDir) ?? '';
        $backFileName = $this->saveBase64Image($backImageData, $uploadDir) ?? '';
        $RSleeveFileName = $this->saveBase64Image($RsleeveImageData, $uploadDir) ?? '';
        $LSleeveFileName = $this->saveBase64Image($LsleeveImageData, $uploadDir) ?? '';


        $uploadedImageFileNames = [];
        if (!empty($uploadedImagesJson)) {
            $uploadedImages = json_decode($uploadedImagesJson, true);

            if (is_array($uploadedImages)) {
                foreach ($uploadedImages as $base64Image) {
                    $fileName = $this->saveBase64Image($base64Image, $uploadDir);
                    if ($fileName) {
                        $uploadedImageFileNames[] = $fileName;
                    }
                }
            }
        }
        
        if (!$frontFileName && !$backFileName && !$RSleeveFileName && !$LSleeveFileName) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to save any images.'
            ]);
        }

        $imageDataToSave = [
            'pr_Id' => $prId,
            'pri_Id' => $priId,
            'cust_Id' => $userId,
            'front_Image' => $frontFileName,
            'back_Image' => $backFileName,
            'RSleeve_Image' => $RSleeveFileName ?? '',
            'LSleeve_Image' => $LSleeveFileName ?? '',
            'User_Upload_Image' => json_encode($uploadedImageFileNames) ?? '',
            'created_on' => date('Y-m-d H:i:s')
        ];

        $designId = $this->tshirtModel->insertDesign($imageDataToSave);

        $cartData = [
            'cust_Id' => $userId,
            'pr_Id' => $prId,
            'pri_Id' => $priId,
            'design_Id' => $designId,
            'created_on' => date('Y-m-d H:i:s'),
            'cart_Size' => $selectedSize,
            'cart_Quantity' => $quantity ?? 1,
            'cart_Price' => $quantity > 0 ? ($totalPrice / $quantity) : $totalPrice
        ];

        $this->CartModel->insert($cartData);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Design saved successfully',
            'file_name' => [
                'front' => $frontFileName,
                'back' => $backFileName,
                'RSleeve_Image' => $RSleeveFileName,
                'LSleeve_Image' => $LSleeveFileName
            ],
            'design_Id' => $designId,
            'redirect' => base_url('cart/' . $userId)
        ]);
    }
    private function saveBase64Image($imageData, $uploadDir)
    {
        if (!$imageData)
            return null;

        if (strpos($imageData, ';base64,') === false) {
            log_message('error', 'Invalid base64 format');
            return null;
        }

        $imageParts = explode(";base64,", $imageData);
        $imageBase64 = base64_decode($imageParts[1], true);

        if ($imageBase64 === false) {
            log_message('error', 'Failed to decode base64');
            return null;
        }

        $fileName = uniqid('', true) . '.jpg';
        $filePath = $uploadDir . $fileName;

        if (!file_put_contents($filePath, $imageBase64)) {
            log_message('error', 'Failed to write image file: ' . $filePath);
            return null;
        }

        return $fileName;
    }

}
