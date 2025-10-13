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
        $imageData = $this->request->getPost('image');
        $prId = $this->request->getPost('prId');
        $priId = $this->request->getPost('priId');
        if (!$imageData) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No image received'
            ]);
        }

        $imageParts = explode(";base64,", $imageData);
        $imageBase64 = base64_decode($imageParts[1]);

        $newName = uniqid('', true) . '.jpg';
        $destination = FCPATH . 'uploads/designs/' . $newName;

        if (!is_dir(FCPATH . 'uploads/designs')) {
            mkdir(FCPATH . 'uploads/designs', 0777, true);
        }

        if (!file_put_contents($destination, $imageBase64)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to save image to server'
            ]);
        }

        $imageDataToSave = [
            'pr_Id' => $prId,
            'pri_Id' => $priId,
            'cust_Id' => $userId,
            'design_Image' => json_encode([$newName]),
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
            'file_name' => $newName,
            'design_id' => $designId,
            'redirect' => base_url('cart/' . $userId)
        ]);
    }

}
