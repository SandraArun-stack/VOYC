<?php
namespace App\Controllers;

use App\Models\tshirtModel;
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
    }

    public function index()
    {
        return view('common/header')
            . view('tshirt')
            . view('common/footer')
            . view('pagescripts/tshirtjs');
    }
    public function saveDesign()
    {
        $imageData = $this->request->getPost('image');

        if (!$imageData) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No image received'
            ]);
        }

        // --- Extract base64 data ---
        $imageParts = explode(";base64,", $imageData);
        $imageBase64 = base64_decode($imageParts[1]);

        // --- Generate random file name like: 68df8b8dbc5650.94542329.jpg ---
        $newName = uniqid('', true) . '.jpg';
        $destination = FCPATH . 'uploads/designs/' . $newName;

        // --- Ensure directory exists ---
        if (!is_dir(FCPATH . 'uploads/designs')) {
            mkdir(FCPATH . 'uploads/designs', 0777, true);
        }

        // --- Save the decoded image to disk ---
        if (!file_put_contents($destination, $imageBase64)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to save image to server'
            ]);
        }

        // --- Prepare image data ---
        $imageDataToSave = [
            'design_Image' => json_encode([$newName]), 
            'created_on' => date('Y-m-d H:i:s')
        ];

        // --- Insert into DB ---
        $designId = $this->tshirtModel->insertDesign($imageDataToSave);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Design saved successfully',
            'file_name' => $newName,
            'design_id' => $designId
        ]);
    }

}
