<?php
namespace App\Controllers;

use App\Models\CartModel;
use CodeIgniter\Controller;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\GameMappingModel;
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
        $this->PlayersModel = new PlayersModel();
        $this->GameMappingModel = new GameMappingModel();

    }

    public function index($userId = null)
    {
        $userId = $userId ?? session()->get('user_id');

        if (!$userId) {
            return redirect()->to(base_url('/'));
        }

        $cartItems = $this->CartModel->getCartItems($userId);

        $cartpriceTotal = $this->CartModel->getCartPrice($userId);

        $cartCount = $this->CartModel->getCartItemCount($userId);

        //leaderboard Count
        $today = date('Y-m-d');
        $todayLimit = $this->GameMappingModel->getTodayLeaderboardCount($today);
        $todayLimit = intval($todayLimit);

        $result = $this->PlayersModel->getTodayPlayers($today, $todayLimit, session()->get('user_id'));


        return view('common/header', [
            'cartCount' => $cartCount,
            'players' => $result['players'],
            'lastPlayer' => $result['lastPlayer']
        ])
            . view('cart', [
                'cartItems' => $cartItems,
                'cartpriceTotal' => $cartpriceTotal
            ])
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

            $session = session();
            $userId = $session->get('user_id');

            $cartCount = $userId ?
                $cartModel->where('cust_Id', $userId)
                    ->where('cart_Status', 1)
                    ->countAllResults()
                : 0;

            return $this->response->setJSON([
                'status' => 'success',
                'cartCount' => $cartCount
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update']);
    }

    public function updateQuantity()
    {
        $cartId = $this->request->getPost('cart_id');
        $quantity = $this->request->getPost('quantity');

        if (!$cartId || !$quantity) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid input data'
            ]);
        }

        $cartModel = new \App\Models\CartModel();
        $cartModel->update($cartId, ['cart_Quantity' => $quantity]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Quantity updated successfully'
        ]);
    }
    public function updateCartSize()
    {
        $cartId = $this->request->getPost('cart_Id');
        $prvId = $this->request->getPost('prv_Id');
        $cartSize = $this->request->getPost('cart_Size');
        $cartPrice = $this->request->getPost('cart_Price');

        if (empty($cartId) || empty($cartSize)) {
            return $this->response->setJSON(['status' => 0, 'message' => 'Invalid data']);
        }

        $updated = $this->CartModel->update($cartId, [
            'prv_Id' => $prvId,
            'cart_Size' => $cartSize,
            'cart_Price' => $cartPrice
        ]);

        if ($updated) {
            return $this->response->setJSON(['status' => 1, 'message' => 'Cart updated successfully']);
        } else {
            return $this->response->setJSON(['status' => 0, 'message' => 'Failed to update cart']);
        }
    }


}
