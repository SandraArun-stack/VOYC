<?php

namespace App\Controllers;
use App\Models\NewProductModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Home extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }
    public function index()
    {
        $newProductModel = new NewProductModel();
        $data['newPrdImg'] = $newProductModel->getNewPrdImage();
        $data['bestSeller'] = $newProductModel->getBestSeller();
        return view('common/header')
            . view('index', $data)
            . view('common/footer')
            . view('pagescripts/indexjs');
    }
}

