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
       
        $template = view('Admin/common/header');
		$template.= view('Admin/common/leftmenu');
		$template.= view('Admin/settings');
        $template.= view('Admin/common/footer');
        $template.= view('Admin/page_scripts/settingsjs');
        return $template;

        
    }
   


}