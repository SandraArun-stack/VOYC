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
        // Get logged-in user ID from session
        $userId = $this->session->get('user_id'); // change to your session key

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

    // Place order
    public function placeOrder()
    {
        $userId = $this->session->get('user_id'); // logged-in user
        $createdBy = $userId;

        $products = $this->request->getPost('products'); 
        if (!$products) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No products found']);
        }

        // Save billing address
        $addressData = [
            'add_Name' => $this->request->getPost('add_Name'),
            'add_BuldingNo' => $this->request->getPost('add_BuldingNo'),
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

        $addressModel = new AddressModel();
        $add_Id = $addressModel->insert($addressData); // insert and get new address ID

        // Save order items
        foreach ($products as $item) {
            $orderData = [
                'cus_Id' => $userId,
                'add_Id' => $add_Id, // use saved address
                'od_Shipping_Address' => $item['od_Shipping_Address'] ?? null,
                'od_createdby' => $createdBy,
                'pr_Id' => $item['pr_Id'],
                'od_Quantity' => $item['od_Quantity'],
                'od_Size' => $item['od_Size'] ?? null,
                'od_Color' => $item['od_Color'] ?? null,
                'od_Original_Price' => $item['od_Original_Price'],
                'od_Selling_Price' => $item['od_Selling_Price'],
                'od_DiscountValue' => $item['od_DiscountValue'] ?? 0,
                'od_DiscountType' => $item['od_DiscountType'] ?? null,
                'pr_Code' => $item['pr_Code'],
                'od_Grand_Total' => $item['od_Grand_Total']
            ];

            $this->orderModel->createOrderItem($orderData);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Order placed successfully']);
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
