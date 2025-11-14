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
        $totalAmount = $this->request->getPost('totalAmount');
        $userId = $this->session->get('user_id');

        if (!$userId) {
            return redirect()->to(base_url('/'));
        }

        $cartModel = new CartModel();
        $cartItems = $cartModel->getCartItems($userId);
        // print_r($cartItems);
        return view('common/header')
            . view('orderdetails', ['cartItems' => $cartItems, 'totalAmount' => $totalAmount])
            . view('common/footer')
            . view('pagescripts/orderdetailsjs');
    }




    //  public function placeOrder()
    // {
    //     $userId = $this->session->get('user_id');
    //     $createdBy = $userId;

    //     // Decode products JSON from JS
    //     $productsJson = $this->request->getPost('products');
    //     $products = json_decode($productsJson, true);
    //     // print_r($products);exit;
    //     if (!$products || empty($products)) {
    //         return $this->response->setJSON(['status' => 'error', 'message' => 'No products found']);
    //     }

    //     // Save billing address
    //     $addressData = [
    //         'add_Name' => $this->request->getPost('add_Name'),
    //         'add_Landmark' => $this->request->getPost('add_Landmark'),
    //         'add_Street' => $this->request->getPost('add_Street'),
    //         'add_City' => $this->request->getPost('add_City'),
    //         'add_State' => $this->request->getPost('add_State'),
    //         'add_Pincode' => $this->request->getPost('add_Pincode'),
    //         'add_Phone' => $this->request->getPost('add_Phone'),
    //         'add_Email' => $this->request->getPost('add_Email'),
    //         'add_CustId' => $userId,
    //         'add_Status' => 'Active',
    //         'add_createdon' => date('Y-m-d H:i:s'),
    //         'add_createdby' => $createdBy
    //     ];

    //     $addressModel = new \App\Models\AddressModel();
    //     $add_Id = $addressModel->insert($addressData);

    //     $shippingAddress = implode(', ', array_filter([
    //         $addressData['add_Name'],
    //         $addressData['add_Landmark'],
    //         $addressData['add_Street'],
    //         $addressData['add_City'],
    //         $addressData['add_State'],
    //         $addressData['add_Pincode'],
    //         $addressData['add_Phone'],
    //         $addressData['add_Email']
    //     ]));

    //     // ----- Generate Order Number starting from 10000 -----
    //     $orderModel = new OrderDetailsModel();
    //     $lastOrder = $orderModel->orderBy('od_Id', 'DESC')->first();
    //     $nextNumber = isset($lastOrder['od_number']) ? ((int) substr($lastOrder['od_number'], -5) + 1) : 10000;

    //     $orderNumber = 'VOYC-' . date('Ymd') . '-' . $nextNumber;

    //     // Initialize variables for product HTML table
    //     $productRows = "";
    //     $totalAmount = 0;

    //     // Save each order item
    //     foreach ($products as $item) {

    //         $item['cus_Id'] = $userId;
    //         $item['add_Id'] = $add_Id;
    //         $item['od_number'] = $orderNumber;
    //         $item['od_Shipping_Address'] = $shippingAddress;

    //         $this->orderModel->createOrderItem($item);

    //         // Accumulate total
    //         $totalAmount += $item['od_Grand_Total'];

    //         // Build table rows
    //         $productRows .= "
    //         <tr>
    //             <td style='padding:8px;border:1px solid #ccc;'>{$item['pr_Code']}</td>
    //             <td style='padding:8px;border:1px solid #ccc;'>{$item['pr_Name']}</td>
    //             <td style='padding:8px;border:1px solid #ccc;'>{$item['od_Size']}</td>
    //             <td style='padding:8px;border:1px solid #ccc;'>{$item['od_Quantity']}</td>
    //             <td style='padding:8px;border:1px solid #ccc;'>₹{$item['od_Selling_Price']}</td>
    //             <td style='padding:8px;border:1px solid #ccc;'>₹{$item['od_Grand_Total']}</td>
    //         </tr>
    //     ";
    //     }

    //     // PRODUCT TABLE HTML
    //     $productTable = "
    //     <table style='width:100%;border-collapse:collapse;margin-top:20px;font-size:14px;'>
    //         <thead>
    //             <tr>
    //                 <th style='padding:10px;border:1px solid #ccc;background:#eee;'>Product Code</th>
    //                 <th style='padding:10px;border:1px solid #ccc;background:#eee;'>Product Name</th>
    //                 <th style='padding:10px;border:1px solid #ccc;background:#eee;'>Size</th>
    //                 <th style='padding:10px;border:1px solid #ccc;background:#eee;'>Qty</th>
    //                 <th style='padding:10px;border:1px solid #ccc;background:#eee;'>Price</th>
    //                 <th style='padding:10px;border:1px solid #ccc;background:#eee;'>Total</th>
    //             </tr>
    //         </thead>
    //         <tbody>
    //             $productRows
    //             <tr>
    //                 <td colspan='5' style='padding:10px;border:1px solid #ccc;text-align:right;font-weight:bold;'>Grand Total</td>
    //                 <td style='padding:10px;border:1px solid #ccc;font-weight:bold;'>₹$totalAmount</td>
    //             </tr>
    //         </tbody>
    //     </table>
    // ";

    //     // Clear cart
    //     (new \App\Models\CartModel())->clearCart($userId);

    //     $email = \Config\Services::email();
    //     $logoUrl = base_url() . ASSET_PATH . "assets/img/logo-black.jpg";

    //     $emailHeader = "
    //     <div style='text-align:center;'>
    //         <img src='{$logoUrl}' style='width:160px;margin-bottom:20px;'>
    //     </div>
    // ";

    //     // ============================
    //     // CUSTOMER EMAIL
    //     // ============================
    //     $customerMessage = "
    //     {$emailHeader}
    //     <p>Hello {$addressData['add_Name']},</p>
    //     <p>Thank you for your order! Your order number is <b>{$orderNumber}</b>.</p>

    //     <h3>Order Summary:</h3>
    //     {$productTable}

    //     <h3>Shipping Address:</h3>
    //     <p>{$shippingAddress}</p>

    //     <p>Best Regards,<br><b>Voyc Team</b></p>
    // ";

    //     $email->setFrom('smartloungework@gmail.com', 'Voyc');
    //     $email->setTo($addressData['add_Email']);
    //     $email->setSubject("Order Confirmation - {$orderNumber}");
    //     $email->setMessage($customerMessage);
    //     $email->setMailType('html');
    //     $email->send();

    //     // ============================
    //     // ADMIN EMAIL
    //     // ============================
    //     $adminMessage = "
    //     {$emailHeader}
    //     <p><b>New Order Received</b></p>
    //     <p><b>Order Number:</b> {$orderNumber}</p>
    //     <p><b>Customer Name:</b> {$addressData['add_Name']}</p>
    //     <p><b>Email:</b> {$addressData['add_Email']}</p>

    //     <h3>Products:</h3>
    //     {$productTable}

    //     <h3>Shipping Address:</h3>
    //     <p>{$shippingAddress}</p>

    //     <p>Order Time: " . date('d M Y, h:i A') . "</p>
    // ";

    //     $email->setTo("smartloungework@gmail.com");
    //     $email->setSubject("New Order Received - {$orderNumber}");
    //     $email->setMessage($adminMessage);
    //     $email->send();

    //     return $this->response->setJSON([
    //         'status' => 'success',
    //         'message' => 'Order placed successfully'
    //     ]);
    // }


    public function placeOrder()
    {
        $userId = $this->session->get('user_id');
        $createdBy = $userId;

        // Decode products JSON
        $productsJson = $this->request->getPost('products');
        $products = json_decode($productsJson, true);

        if (!$products || empty($products)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No products found']);
        }

        // ============= SAVE ADDRESS =============
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

        $shippingAddress = implode(', ', array_filter($addressData));

        // ============= GENERATE ORDER NUMBER =============
        $orderModel = new OrderDetailsModel();
        $lastOrder = $orderModel->orderBy('od_Id', 'DESC')->first();
        $nextNumber = $lastOrder ? ((int) substr($lastOrder['od_number'], -5) + 1) : 10000;

        $orderNumber = 'VOYC-' . date('Ymd') . '-' . $nextNumber;

        $totalAmount = 0;
        $productRows = "";

        foreach ($products as $item) {

            // $item['od_Id'] = $mainOrderId;
            $item['od_number'] = $orderNumber;
            $item['cus_Id'] = $userId;
            $item['add_Id'] = $add_Id;
            $item['od_Shipping_Address'] = $shippingAddress;

            $this->orderModel->createOrderItem($item);

            $totalAmount += $item['od_Grand_Total'];

            $productRows .= "
        <tr>
            <td style='padding:8px;border:1px solid #ccc;'>{$item['pr_Code']}</td>
            <td style='padding:8px;border:1px solid #ccc;'>{$item['pr_Name']}</td>
            <td style='padding:8px;border:1px solid #ccc;'>{$item['od_Size']}</td>
            <td style='padding:8px;border:1px solid #ccc;'>{$item['od_Quantity']}</td>
            <td style='padding:8px;border:1px solid #ccc;'>₹{$item['od_Selling_Price']}</td>
            <td style='padding:8px;border:1px solid #ccc;'>₹{$item['od_Grand_Total']}</td>
        </tr>";
        }

        $productTable = "
    <table style='width:100%;border-collapse:collapse;margin-top:20px;font-size:14px;'>
        <thead>
            <tr>
                <th style='padding:10px;border:1px solid #ccc;background:#eee;'>Product Code</th>
                <th style='padding:10px;border:1px solid #ccc;background:#eee;'>Product Name</th>
                <th style='padding:10px;border:1px solid #ccc;background:#eee;'>Size</th>
                <th style='padding:10px;border:1px solid #ccc;background:#eee;'>Qty</th>
                <th style='padding:10px;border:1px solid #ccc;background:#eee;'>Price</th>
                <th style='padding:10px;border:1px solid #ccc;background:#eee;'>Total</th>
            </tr>
        </thead>
        <tbody>
            $productRows
                <tr>
                    <td colspan='5' style='padding:10px;border:1px solid #ccc;text-align:right;font-weight:bold;'>Grand Total</td>
                    <td style='padding:10px;border:1px solid #ccc;font-weight:bold;'>₹$totalAmount</td>
                </tr>
        </tbody>
    </table>";

        // Clear cart
        (new \App\Models\CartModel())->clearCart($userId);

        $email = \Config\Services::email();
        $logoUrl = base_url() . ASSET_PATH . "assets/img/logo-black.jpg";

        $emailHeader = "
        <div style='text-align:center;'>
            <img src='{$logoUrl}' style='width:160px;margin-bottom:20px;'>
        </div>
    ";

        // ============================
        // CUSTOMER EMAIL
        // ============================
        $customerMessage = "
        {$emailHeader}
        <p>Hello {$addressData['add_Name']},</p>
        <p>Thank you for your order! Your order number is <b>{$orderNumber}</b>.</p>

        <h3>Order Summary:</h3>
        {$productTable}

        <h3>Shipping Address:</h3>
        <p>{$shippingAddress}</p>

        <p>Best Regards,<br><b>Voyc Team</b></p>
    ";

        $email->setFrom('smartloungework@gmail.com', 'Voyc');
        $email->setTo($addressData['add_Email']);
        $email->setSubject("Order Confirmation - {$orderNumber}");
        $email->setMessage($customerMessage);
        $email->setMailType('html');
        $email->send();

        // ============================
        // ADMIN EMAIL
        // ============================
        $adminMessage = "
        {$emailHeader}
        <p><b>New Order Received</b></p>
        <p><b>Order Number:</b> {$orderNumber}</p>
        <p><b>Customer Name:</b> {$addressData['add_Name']}</p>
        <p><b>Email:</b> {$addressData['add_Email']}</p>

        <h3>Products:</h3>
        {$productTable}

        <h3>Shipping Address:</h3>
        <p>{$shippingAddress}</p>

        <p>Order Time: " . date('d M Y, h:i A') . "</p>
    ";

        $email->setTo("smartloungework@gmail.com");
        $email->setSubject("New Order Received - {$orderNumber}");
        $email->setMessage($adminMessage);
        $email->send();

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
