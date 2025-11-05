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
            return $this->response->setJSON(['status' => 'error', 'message' => 'No products found']);
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

        $shippingAddress = implode(', ', array_filter([
            $addressData['add_Name'],
            $addressData['add_Landmark'],
            $addressData['add_Street'],
            $addressData['add_City'],
            $addressData['add_State'],
            $addressData['add_Pincode'],
            $addressData['add_Phone'],
            $addressData['add_Email']
        ]));

        $orderNumber = 'VOYC-' . date('Ymd') . '-' . random_int(10000, 99999);

        // Save each order item
        foreach ($products as $item) {
            $item['cus_Id'] = $userId;
            $item['add_Id'] = $add_Id;
            $item['od_number'] = $orderNumber;
            $item['od_Shipping_Address'] = $shippingAddress;
            $this->orderModel->createOrderItem($item);
        }

        // Clear cart
        $cartModel = new \App\Models\CartModel();
        $cartModel->clearCart($userId);
        $email = \Config\Services::email();
        $logoUrl = base_url() . ASSET_PATH . "assets/img/logo-black.jpg";

        // Common HTML email header
        $emailHeader = "
        <div style='text-align:center;'>
            <img src='{$logoUrl}' alt='Voyc Logo' style='max-width:180px;height:auto;margin-bottom:20px;'>
        </div>
    ";

        // ==============================
        // EMAIL TO CUSTOMER
        // ==============================
        $customerMessage = "
        <div style='max-width:600px;margin:auto;border:1px solid #eee;border-radius:10px;padding:20px;font-family:Arial,sans-serif;'>
            {$emailHeader}
            <p>Hello {$addressData['add_Name']},</p>
            <p>Thank you for your order! Your order number is <strong>{$orderNumber}</strong>.</p>
            <p>Shipping to:</p>
            <p>{$shippingAddress}</p>
            <p>We'll notify you once your order is shipped.</p>
            <p style='margin-top:30px;'>Best regards,<br><b>The Voyc Team</b></p>
        </div>
    ";

        $email->clear();
        $email->setFrom('smartloungework@gmail.com', 'Voyc');
        $email->setTo($addressData['add_Email']);
        $email->setSubject("Order Confirmation - {$orderNumber}");
        $email->setMessage($customerMessage);
        $email->setMailType('html');
        $email->send();

        // ==============================
        // EMAIL TO ADMIN
        // ==============================
        $adminEmail = 'smartloungework@gmail.com'; 
        $adminMessage = "
        <div style='max-width:600px;margin:auto;border:1px solid #eee;border-radius:10px;padding:20px;font-family:Arial,sans-serif;'>
            {$emailHeader}
            <p><strong>New order received!</strong></p>
            <p><b>Order Number:</b> {$orderNumber}</p>
            <p><b>Customer:</b> {$addressData['add_Name']}</p>
            <p><b>Email:</b> {$addressData['add_Email']}</p>
            <p><b>Shipping Address:</b><br>{$shippingAddress}</p>
            <p>Placed on: " . date('d M Y, h:i A') . "</p>
        </div>
    ";

        $email->clear();
        $email->setFrom('smartloungework@gmail.com', 'Voyc');
        $email->setTo($adminEmail);
        $email->setSubject("New Order Received - {$orderNumber}");
        $email->setMessage($adminMessage);
        $email->setMailType('html');
        $email->send();

        // ==============================
        // RESPONSE
        // ==============================
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Order placed successfully'
        ]);
    }
    public function saveAddress()
    {
        $userId = $this->session->get('user_id'); // Get logged-in user
        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'User not logged in']);
        }

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
