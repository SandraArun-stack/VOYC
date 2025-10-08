<?php
namespace App\Controllers;

use App\Models\ShopModel;
use CodeIgniter\Controller;

class Contact extends Controller
{
    protected $session;
    protected $request;
    protected $ShopModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->ShopModel = new ShopModel();
    }

    public function index()
    {
        return view('common/header')
            . view('contact')
            . view('common/footer');
    }
}
