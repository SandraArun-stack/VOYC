<?php
namespace App\Controllers;

use App\Models\ShopModel;
use CodeIgniter\Controller;

class Shop extends Controller
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
        $uri = service('uri');
        $segment = $uri->getSegment(1); 

        if ($segment !== 'men' && $segment !== 'women') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'category' => $segment,
            'title' => ucfirst($segment) . ' Shop',
            'breadcrumb' => ucfirst($segment),
        ];

        return view('common/header')
            . view('shop', $data)
            . view('common/footer');
    }

}
