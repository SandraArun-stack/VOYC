<?php
namespace App\Controllers;

use App\Models\CartModel;
use CodeIgniter\Controller;

class Cart extends Controller
{
    protected $session;
    protected $request;
    protected $CartModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->CartModel = new CartModel();
    }

    public function index($userId = null)
    {

        if (!$userId) {
            return redirect()->to(base_url('/'));
        }

        $cartItems = $this->CartModel->getCartItems($userId);

        return view('common/header')
            . view('cart', ['cartItems' => $cartItems])
            . view('common/footer')
            . view('pagescripts/cartjs');
    }
    public function remove()
    {
        $cartId = $this->request->getPost('cart_Id');

        if (!$cartId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cart ID missing']);
        }

        $cartModel = new \App\Models\CartModel();

        $updated = $cartModel->update($cartId, ['cart_Status' => 0]);

        if ($updated) {
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update']);
        }
    }

}
