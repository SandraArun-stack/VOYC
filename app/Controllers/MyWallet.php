<?php

namespace App\Controllers;
use App\Models\NewProductModel;
use App\Models\HomeModel;
use App\Models\CartModel;
use App\Models\Admin\LeaderboardModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class mywallet extends BaseController
{
    protected $HomeModel;
    protected $categories;
    protected $session;
    protected $request;
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->productdisplayModel = new HomeModel();
        $this->reviewModel = new NewProductModel();
        $this->CartModel = new CartModel();
        $this->LeaderboardModel = new LeaderboardModel();
    }



    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');
        $cartCount = $this->CartModel->getCartItemCount($userId);

        return view('common/header', ['cartCount' => $cartCount])
            . view('common/UserSideBar')
            . view('my_wallet')
            . view('common/footer')
            . view('pagescripts/mywalletjs');
    }



}

