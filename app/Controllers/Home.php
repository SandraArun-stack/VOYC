<?php

namespace App\Controllers;

use App\Models\ProductDisplayModel;
use App\Models\Admin\Theme_Model;
use App\Models\ReviewModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Home extends BaseController
{


    // public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    // {
    //     parent::initController($request, $response, $logger);

    //     $this->productdisplayModel = new ProductDisplayModel();
    //     $this->categories = $this->productdisplayModel->getAllCategoriesAndSub();
    // }

    public function index()
    {

        $template = view('index');
        return $template;
    }

}
