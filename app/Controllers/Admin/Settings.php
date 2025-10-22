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
        $chargeData = $settingsModel->getCustomizationCharge();

        // 👇 Prepare data for view
        $data = [
            'customization_charge' => $chargeData['value'] ?? ''
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
        $value = $this->request->getPost('customization_charge');

        if ($value === null || $value === '') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Value is required']);
        }
        if (!is_numeric($value)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Enter a Numeric Value.']);
        }


        $settingsModel = new \App\Models\Admin\SettingsModel();
        $updated = $settingsModel->updateCustomizationCharge($value);

        if ($updated) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Customization charge updated successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update']);
        }
    }



}