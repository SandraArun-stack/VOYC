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
            'sleeve_Customization_Price' => $chargeData['sleeve_Customization_Price'] ?? '',
            'shipping_charge' => $chargeData['shipping_charge'] ?? '',
            'minimum_amount_for_shipping_charge' => $chargeData['minimum_amount_for_shipping_charge'] ?? '',
            'token_price_for_per_piece' => $chargeData['token_price_for_per_piece'] ?? ''
            // 'token_price' => $chargeData['token_price'] ?? ''
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
        $shipping_charge = $this->request->getPost('shipping_charge');
        $minimum_amount_for_shipping_charge = $this->request->getPost('minimum_amount_for_shipping_charge');
        $token_price_for_per_piece = $this->request->getPost('token_price_for_per_piece');

        if (is_null($frontCharge) || $frontCharge === '' || !is_numeric($frontCharge)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Front Customization Price Must be a Numeric Value.']);
        }

        if (is_null($backCharge) || $backCharge === '' || !is_numeric($backCharge)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Back Customization Price Must be a Numeric Value.']);
        }

        if (is_null($sleeveCharge) || $sleeveCharge === '' || !is_numeric($sleeveCharge)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Sleeve Customization Price Must be a Numeric Value.']);
        }

        if (is_null($shipping_charge) || $shipping_charge === '' || !is_numeric($shipping_charge)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Leaderboard Count Must be a Numeric Value.']);
        }

        if ($minimum_amount_for_shipping_charge === '' || !is_numeric($minimum_amount_for_shipping_charge) || $minimum_amount_for_shipping_charge < 0 ) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Amount for Free Shipping Must be a Non-Negative Numeric Value.']);
        }

        if ($token_price_for_per_piece === '' || !is_numeric($token_price_for_per_piece) || $token_price_for_per_piece < 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Token Price per Piece Must be a Numeric Value between 0 and 100.']);
        }

        $settingsModel = new \App\Models\Admin\SettingsModel();
        $updatedFront = $settingsModel->updateCustomizationCharge('front_Customization_Price', $frontCharge);
        $updatedBack = $settingsModel->updateCustomizationCharge('back_Customization_Price', $backCharge);
        $updatedSleeve = $settingsModel->updateCustomizationCharge('sleeve_Customization_Price', $sleeveCharge);
        $shipping_charge = $settingsModel->updateCustomizationCharge('shipping_charge', $shipping_charge);
        $minimum_amount_for_shipping_charge = $settingsModel->updateCustomizationCharge('minimum_amount_for_shipping_charge', $minimum_amount_for_shipping_charge);
        $token_price_for_per_piece = $settingsModel->updateCustomizationCharge('token_price_for_per_piece', $token_price_for_per_piece);

        if ($updatedFront && $updatedBack && $updatedSleeve) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Customization Charges Updated Successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to Update Customization Charges']);
        }

    }


}