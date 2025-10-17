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

    // public function index($prId = null, $priId = null)
    // {
    //     if (!empty($prId) && !empty($priId)) {
    //         $cust_image = $this->tshirtModel->get_Image($prId, $priId);
    //         $allData = $this->tshirtModel->get_Data_For_Pr_Id($prId);
    //         if (!$cust_image) {
    //             return redirect()->to(base_url());
    //         } else {
    //             $data = [
    //                 'prId' => $prId,
    //                 'priId' => $priId,
    //                 'cust_image' => $cust_image,
    //                 'allData' => $allData
    //             ];
    //             return view('common/header')
    //                 . view('tshirt', $data)
    //                 . view('common/footer')
    //                 . view('pagescripts/tshirtjs');
    //         }

    //     } else {
    //         return view('common/header')
    //             . view('tshirt')
    //             . view('common/footer')
    //             . view('pagescripts/tshirtjs');
    //     }

    // }

    public function index($prId = null, $priId = null)
    {
        if (!empty($prId) && !empty($priId)) {
            $cust_image = $this->tshirtModel->get_Image($prId, $priId);
            $allData = $this->tshirtModel->get_Data_For_Pr_Id($prId);

            if (!$cust_image) {
                return redirect()->to(base_url());
            } else {
                // Pass all necessary data, including images
                $data = [
                    'prId' => $prId,
                    'priId' => $priId,
                    'cust_image' => $cust_image,
                    'allData' => $allData
                ];
                return view('common/header')
                    . view('tshirt', $data)
                    . view('common/footer')
                    . view('pagescripts/tshirtjs');
            }

        } else {
            return view('common/header')
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

        $frontImageData = $this->request->getPost('front');
        $backImageData = $this->request->getPost('back');
        $sleeveImageData = $this->request->getPost('sleeve');
        $prId = $this->request->getPost('prId');
        $priId = $this->request->getPost('priId');
        $prvId = $this->request->getPost('prvId');

        if (empty($prvId)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Select the Size You Want.'
            ]);
        }


        // echo $prvId;exit();
        if (!$frontImageData && !$backImageData && !$sleeveImageData) {
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
        $frontFileName = $this->saveBase64Image($frontImageData, $uploadDir);
        $backFileName = $this->saveBase64Image($backImageData, $uploadDir);
        $sleeveFileName = $this->saveBase64Image($sleeveImageData, $uploadDir);

        if (!$frontFileName && !$backFileName && !$sleeveFileName) {
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
            'sleeve_Image' => $sleeveFileName,
            'created_on' => date('Y-m-d H:i:s')
        ];

        $designId = $this->tshirtModel->insertDesign($imageDataToSave);

        $cartData = [
            'cust_Id' => $userId,
            'pr_Id' => $prId,
            'pri_Id' => $priId,
            'design_Id' => $designId,
            'created_on' => date('Y-m-d H:i:s'),
            'prv_Id' => $prvId,
            'cart_Quantity' => 1

        ];

        $this->CartModel->insert($cartData);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Design saved successfully',
            'file_name' => [
                'front' => $frontFileName,
                'back' => $backFileName,
                'sleeve' => $sleeveFileName
            ],
            'design_id' => $designId,
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
