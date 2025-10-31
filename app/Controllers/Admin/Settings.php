<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Admin\SettingsModel;

class Settings extends BaseController
{

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->input = \Config\Services::request();
        $this->SettingsModel = new \App\Models\Admin\SettingsModel();
    }

    public function index()
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to(base_url('admin'));
        }

        $settingsModel = new \App\Models\Admin\SettingsModel();
        $chargeData = $settingsModel->getCustomizationCharges(); 

        $data = [
            'front_Customization_Price' => $chargeData['front_Customization_Price'] ?? '',
            'back_Customization_Price' => $chargeData['back_Customization_Price'] ?? '',
            'sleeve_Customization_Price' => $chargeData['sleeve_Customization_Price'] ?? ''
        ];


        $template = view('Admin/common/header');
        $template .= view('Admin/common/leftmenu');
        $template .= view('Admin/settings', $data);
        $template .= view('Admin/common/footer');
        $template .= view('Admin/page_scripts/settingsjs');
        return $template;

    }



    public function updateCustomizationCharge()
    {
        // Get customization prices from the POST request
        $frontCharge = $this->request->getPost('front_Customization_Price');
        $backCharge = $this->request->getPost('back_Customization_Price');
        $sleeveCharge = $this->request->getPost('sleeve_Customization_Price');

        if (is_null($frontCharge) || $frontCharge === '' || !is_numeric($frontCharge)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Front Customization Price Must be a Numeric Value.']);
        }

        if (is_null($backCharge) || $backCharge === '' || !is_numeric($backCharge)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Back Customization Price Must be a Numeric Value.']);
        }

        if (is_null($sleeveCharge) || $sleeveCharge === '' || !is_numeric($sleeveCharge)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Sleeve Customization Price Must be a Numeric Value.']);
        }

        $settingsModel = new \App\Models\Admin\SettingsModel();
        $updatedFront = $settingsModel->updateCustomizationCharge('front_Customization_Price', $frontCharge);
        $updatedBack = $settingsModel->updateCustomizationCharge('back_Customization_Price', $backCharge);
        $updatedSleeve = $settingsModel->updateCustomizationCharge('sleeve_Customization_Price', $sleeveCharge);

        if ($updatedFront && $updatedBack && $updatedSleeve) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Customization Charges Updated Successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to Update Customization Charges']);
        }
    }


}