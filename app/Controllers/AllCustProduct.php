<?php
namespace App\Controllers;

use App\Models\AllCustProductModel;
use CodeIgniter\Controller;

class AllCustProduct extends Controller
{
    protected $session;
    protected $request;
    protected $AllCustProductModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->AllCustProductModel = new AllCustProductModel();
    }

    public function index($userId = null)
    {
        $data['customizable_products'] = $this->AllCustProductModel->getAllCustomProducts();

        return view('common/header')
            . view('all_cust_products', $data)
            . view('common/footer')
            . view('pagescripts/all_cust_productsjs');
    }


}
