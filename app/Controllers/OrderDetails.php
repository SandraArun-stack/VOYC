<?php
namespace App\Controllers;

use App\Models\OrderDetailsModel;
use App\Models\AddressModel;
use App\Models\CartModel;
use CodeIgniter\Controller;

class OrderDetails extends Controller
{
    protected $session;
    protected $request;
    protected $orderModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->orderModel = new OrderDetailsModel();
    }

    // Show checkout page
    public function index()
    {
        $userId = $this->session->get('user_id');

        if (!$userId) {
            return redirect()->to(base_url('/'));
        }

        $cartModel = new CartModel();
        $cartItems = $cartModel->getCartItems($userId);
        return view('common/header')
            . view('orderdetails', ['cartItems' => $cartItems])
            . view('common/footer')
            . view('pagescripts/orderdetailsjs');
    }



public function placeOrder()
{
    $userId = $this->session->get('user_id');
    $createdBy = $userId;

    // Decode products JSON from JS
    $productsJson = $this->request->getPost('products');
    $products = json_decode($productsJson, true);

    if (!$products || empty($products)) {
        return $this->response->setJSON(['status'=>'error','message'=>'No products found']);
    }

    // Save billing address
    $addressData = [
        'add_Name' => $this->request->getPost('add_Name'),
        'add_Landmark' => $this->request->getPost('add_Landmark'),
        'add_Street' => $this->request->getPost('add_Street'),
        'add_City' => $this->request->getPost('add_City'),
        'add_State' => $this->request->getPost('add_State'),
        'add_Pincode' => $this->request->getPost('add_Pincode'),
        'add_Phone' => $this->request->getPost('add_Phone'),
        'add_Email' => $this->request->getPost('add_Email'),
        'add_CustId' => $userId,
        'add_Status' => 'Active',
        'add_createdon' => date('Y-m-d H:i:s'),
        'add_createdby' => $createdBy
    ];

    $addressModel = new \App\Models\AddressModel();
    $add_Id = $addressModel->insert($addressData);

    // Save each order item
    foreach($products as $item){
        $item['cus_Id'] = $userId;
        $item['add_Id'] = $add_Id;
        $this->orderModel->createOrderItem($item);
    }

    // Clear cart
    $cartModel = new \App\Models\CartModel();
    $cartModel->clearCart($userId);

    return $this->response->setJSON(['status'=>'success','message'=>'Order placed successfully']);
}


    public function saveAddress()
{
    $userId = $this->session->get('user_id'); // Get logged-in user
    if (!$userId) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'User not logged in']);
    }

    $addressData = [
        'add_Name'      => $this->request->getPost('add_Name'),
        'add_Landmark'  => $this->request->getPost('add_Landmark'),
        'add_Street'    => $this->request->getPost('add_Street'),
        'add_City'      => $this->request->getPost('add_City'),
        'add_State'     => $this->request->getPost('add_State'),
        'add_Pincode'   => $this->request->getPost('add_Pincode'),
        'add_Phone'     => $this->request->getPost('add_Phone'),
        'add_Email'     => $this->request->getPost('add_Email'),
        'add_CustId'    => $userId,
        'add_Status'    => 'Active',
        'add_createdon' => date('Y-m-d H:i:s'),
        'add_createdby' => $userId
    ];

    $addressModel = new \App\Models\AddressModel();
    $inserted = $addressModel->insert($addressData);

    if ($inserted) {
        return $this->response->setJSON(['status' => 'success', 'message' => 'Address saved successfully', 'add_Id' => $inserted]);
    } else {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save address']);
    }
}

}
