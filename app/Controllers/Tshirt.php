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
        if (!empty($prId) && !empty($priId)) {
            $cust_image = $this->tshirtModel->get_Image($prId, $priId);
            if (!$cust_image) {
                return redirect()->to(base_url());
            } else {
                $data = [
                    'prId' => $prId,
                    'priId' => $priId,
                    'cust_image' => $cust_image
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
                // 'message' => 'No image received'
            ]);
        }
        // $imageData = $this->request->getPost('image');
        $frontImageData = $this->request->getPost('front');
        $backImageData = $this->request->getPost('back');
        $sleeveImageData = $this->request->getPost('sleeve');

        $prId = $this->request->getPost('prId');
        $priId = $this->request->getPost('priId');

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

        // Save function
        function saveBase64Image($imageData, $uploadDir)
        {
            if (!$imageData)
                return null;

            $imageParts = explode(";base64,", $imageData);
            if (count($imageParts) !== 2)
                return null;

            $imageBase64 = base64_decode($imageParts[1]);
            $fileName = uniqid('', true) . '.jpg';
            $filePath = $uploadDir . $fileName;

            if (file_put_contents($filePath, $imageBase64)) {
                return $fileName;
            }
            return null;
        }

        // Save images and get filenames
        $frontFileName = saveBase64Image($frontImageData, $uploadDir);
        $backFileName = saveBase64Image($backImageData, $uploadDir);
        $sleeveFileName = saveBase64Image($sleeveImageData, $uploadDir);

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
            'front_Image' => $frontFileName ?? null,
            'back_Image' => $backFileName ?? null,
            'sleeve_Image' => $sleeveFileName ?? null,
            'created_on' => date('Y-m-d H:i:s')
        ];

        $designId = $this->tshirtModel->insertDesign($imageDataToSave);
        $cartData = [
            'cust_Id' => $userId,
            'pr_Id' => $prId,
            'pri_Id' => $priId,
            'design_Id' => $designId,
            'created_on' => date('Y-m-d H:i:s')
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

}
