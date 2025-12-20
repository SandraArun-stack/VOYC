<?php
namespace App\Controllers;
use CodeIgniter\Controller;

use App\Models\OrderDetailsModel;
use App\Models\AddressModel;
use App\Models\CartModel;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\GameMappingModel;
use App\Models\Admin\ProductModel;
use App\Models\UserleaderboardModel;
use App\Models\Admin\CustomerModel;

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
class OrderDetails extends Controller
{
    protected $db;
    protected $session;
    protected $request;
    protected $orderModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->orderModel = new OrderDetailsModel();
        $this->CartModel = new CartModel();
        $this->PlayersModel = new PlayersModel();
        $this->GameMappingModel = new GameMappingModel();
        $this->ProductModel = new ProductModel();
        $this->CustomerModel = new CustomerModel();

    }

    // Show checkout page
    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');


        $user = $this->CustomerModel->getdetailsbyCustomerid($userId);

        $data = [
            'cust_Email' => $user['cust_Email'] ?? '',
            'cust_Phone' => $user['cust_Phone'] ?? '',
        ];


        $cartCount = $this->CartModel->getCartItemCount($userId);

        //leaderboard Count
        $today = date('Y-m-d');
        $todayLimit = $this->GameMappingModel->getTodayLeaderboardCount($today);
        $todayLimit = intval($todayLimit);

        $result = $this->PlayersModel->getTodayPlayers($today, $todayLimit, session()->get('user_id'));


        $totalAmount = $this->request->getPost('totalAmount');
        $userId = $this->session->get('user_id');

        if (!$userId) {
            return redirect()->to(base_url('/'));
        }

        $cartModel = new CartModel();
        $cartItems = $cartModel->getCartItems($userId);

        $shippingData = $this->db->table('common_table')
            ->whereIn('field', [
                'minimum_amount_for_shipping_charge',
                'shipping_charge'
            ])
            ->get()
            ->getResultArray();

        $shipping = [
            'minimum_amount_for_shipping_charge' => 0,
            'shipping_charge' => 0
        ];

        foreach ($shippingData as $row) {
            $shipping[$row['field']] = $row['value'];
        }

        return view('common/header', [
            'cartCount' => $cartCount,
            'players' => $result['players'],
            'lastPlayer' => $result['lastPlayer']
        ])
            . view('orderdetails', [
                'cartItems' => $cartItems,
                'totalAmount' => $totalAmount,
                'cust_Email' => $data['cust_Email'],
                'cust_Phone' => $data['cust_Phone'],
                'minimum_amount_for_shipping_charge' => $shipping['minimum_amount_for_shipping_charge'],
                'shipping_charge' => $shipping['shipping_charge'],
            ])
            . view('common/footer')
            . view('pagescripts/orderdetailsjs');
    }

    public function placeOrder()
    {

        $isSameAsShipping = $this->request->getPost('same_as_shipping');
        $userId = $this->session->get('user_id');
        $lbId = $this->request->getPost('lb_Id');

        if (empty($userId)) {
            return redirect()->to(base_url('/'));
        }

        // ============================
        // RAZORPAY PAYMENT VERIFICATION
        // ============================
        $razorpayPaymentId = $this->request->getPost('razorpay_payment_id');
        $razorpayOrderId = $this->request->getPost('razorpay_order_id');
        $razorpaySignature = $this->request->getPost('razorpay_signature');

        if (!$razorpayPaymentId || !$razorpaySignature || !$razorpayOrderId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Payment verification failed'
            ]);
        }

        $api = new \Razorpay\Api\Api(
            env('RAZORPAY_KEY_ID'),
            env('RAZORPAY_KEY_SECRET')
        );

        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature
            ]);
        } catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid payment signature'
            ]);
        }



        $createdBy = $userId;

        // Decode products JSON
        $productsJson = $this->request->getPost('products');
        $products = json_decode($productsJson, true);

        if (!$products || empty($products)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No products found']);
        }

        // ============= SAVE ADDRESS =============
        $billingAddress = [
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
        $add_Id = $addressModel->insert($billingAddress);

        $shippingAddress = implode(', ', array_filter($billingAddress));

        if ($isSameAsShipping) {

            // ✅ Same address
            $shippingAddress = $billingAddress;

        } else {

            // ✅ Separate shipping address
            $shippingAddress = implode(', ', array_filter([
                $this->request->getPost('shipping_add_Name'),
                $this->request->getPost('shipping_add_Street'),
                $this->request->getPost('shipping_add_Landmark'),
                $this->request->getPost('shipping_add_City'),
                $this->request->getPost('shipping_add_State'),
                $this->request->getPost('shipping_add_Pincode'),
                $this->request->getPost('shipping_add_Country'),
                $this->request->getPost('shipping_add_Phone'),
                $this->request->getPost('shipping_add_Email')
            ]));
        }

        // ============= GENERATE ORDER NUMBER =============
        $orderModel = new OrderDetailsModel();
        $lastOrder = $orderModel->orderBy('od_Id', 'DESC')->first();
        $nextNumber = $lastOrder ? ((int) substr($lastOrder['od_number'], -5) + 1) : 10000;

        $orderNumber = 'VOYC-' . date('Ymd') . '-' . $nextNumber;

        $productRows = "";

        foreach ($products as $item) {

            $item['od_number'] = $orderNumber;
            $item['cus_Id'] = $userId;
            $item['add_Id'] = $add_Id;
            $item['od_Billing_Address'] = $billingAddress;
            $item['od_Shipping_Address'] = $shippingAddress;
            $item['od_createdby'] = $userId;

            $db = \Config\Database::connect();
            $variant = $db->table('product_variants')
                ->select('prv_price')
                ->where('prv_Id', $item['prv_Id'])
                ->where('pr_Id', $item['pr_Id'])
                ->where('pri_Id', $item['pri_Id'])
                ->get()
                ->getRowArray();

            if (!$variant) {
                continue; // skip invalid variant safely
            }

            $item['od_Original_Price'] = (float) $variant['prv_price'];

            // ✅ DISCOUNT VALIDATION
            $discount = (float) ($item['od_DiscountValue'] ?? 0);

            if ($discount > 0 && $discount <= 100) {
                $item['od_Selling_Price'] =
                    $item['od_Original_Price'] -
                    ($item['od_Original_Price'] * $discount / 100);

                $item['od_DiscountValue'] = $discount;
                $item['od_DiscountType'] = '%';
            } else {
                $item['od_Selling_Price'] = $item['od_Original_Price'];
                $item['od_DiscountValue'] = 0;
                $item['od_DiscountType'] = 'NONE';
            }

            // ✅ YOUR REQUIRED FORMULA
            $item['od_Grand_Total'] =
                $item['od_Selling_Price'] * $item['od_Quantity'];

            // Save order item
            $this->orderModel->createOrderItem($item);

            // Order total
            $totalAmount = 0;

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

        $formattedBillingAddress = "
            <div style='line-height:0.2'>
                <p class='m-0'><strong>Name:</strong></p>
                <p class='m-0'>{$billingAddress['add_Name']}</p>

                <br>

                <p class='m-0'><strong>Address:</strong></p>
                <p class='m-0'>{$billingAddress['add_Street']}</p>
                <p class='m-0'>{$billingAddress['add_Landmark']}</p>
                <p class='m-0'>{$billingAddress['add_City']}, {$billingAddress['add_State']}, India – {$billingAddress['add_Pincode']}</p>

                <br>

                <p class='m-0'><strong>Phone:</strong></p>
                <p class='m-0'>+91 {$billingAddress['add_Phone']}</p>

                <br>

                <p class='m-0'><strong>Email:</strong></p>
                <p class='m-0'>{$billingAddress['add_Email']}</p>
            </div>
        ";



        // ============================
        // CUSTOMER EMAIL
        // ============================
        $customerMessage = "
        {$emailHeader}
        <p class='my-0'>Hello {$billingAddress['add_Name']},</p>
        <p class='my-0'>Thank you for your order! Your order number is <b>{$orderNumber}</b>.</p>

        <h3>Order Summary:</h3>
        {$productTable}

        <h3 >Shipping Address:</h3>
        <p class='my-0'>{$formattedBillingAddress}</p>
        <br/>
        <p class='my-0' style='font-size:14px;'>Best Regards,<br><b>Voyc Team</b></p>
    ";

        $email->setFrom('smartloungework@gmail.com', 'Voyc');
        $email->setTo($billingAddress['add_Email']);
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
        <p><b>Customer Name:</b> {$billingAddress['add_Name']}</p>
        <p><b>Email:</b> {$billingAddress['add_Email']}</p>

        <h3>Products:</h3>
        {$productTable}

        <h3>Billing Address:</h3>
       <p class='my-0'>{$formattedBillingAddress}</p>
        <br/>

        <p>Order Time: " . date('d M Y, h:i A') . "</p>
    ";

        $email->setTo("smartloungework@gmail.com");
        $email->setSubject("New Order Received - {$orderNumber}");
        $email->setMessage($adminMessage);
        $email->send();
        // print_r($userId);exit();
        if (!empty($lbId)) {
            // print_r("Updating leaderboard ID: " . $lbId);exit();
            // $this->UserleaderboardModel
            //     ->where('lb_Id', $lbId)
            //     ->where('cust_Id', $userId)
            //     ->update([
            //         'lb_redeemed_status' => '2',
            //         'lb_updated_at' => date('Y-m-d H:i:s'),
            //         'lb_updated_by' => $userId
            //     ]);
            $this->UserleaderboardModel
                ->where('lb_Id', $lbId)
                ->where('cust_Id', $userId)
                ->set([
                    'lb_redeemed_status' => '2',
                    'lb_updated_at' => date('Y-m-d H:i:s'),
                    'lb_updated_by' => $userId
                ])
                ->update();
        }

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

    public function validateCoupon()
    {
        // echo "asna";exit();
        $session = session();
        $userId = $session->get('user_id');

        $coupon = $this->request->getPost('coupen_code');

        if (!$coupon || !$userId) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "Invalid request."
            ]);
        }

        $db = \Config\Database::connect();

        $builder = $db->table('leaderboard');
        $builder->where('lb_coupen_code', $coupon);
        $builder->where('cust_Id', $userId);
        $builder->where('lb_status', '2');           // status 2 → discount
        $builder->where('lb_redeemed_status', '1');  // not redeemed yet

        $result = $builder->get()->getRow();

        if (!$result) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "Invalid Coupon Code or Already Used."
            ]);
        }

        return $this->response->setJSON([
            "status" => "success",
            "message" => "Coupon Applied Successfully.",
            "lb_Id" => $result->lb_Id,
            "discount" => $result->lb_discount ?? 0
        ]);
    }
    public function orderdetailsforbuyfree()
    {
        $session = session();
        $userId = $session->get('user_id');

        if (!$userId) {
            return redirect()->to(base_url('/'));
        }
        $user = $this->CustomerModel->getdetailsbyCustomerid($userId);

        $data = [
            'cust_Email' => $user['cust_Email'] ?? '',
            'cust_Phone' => $user['cust_Phone'] ?? '',
        ];

        // Get cart count (for header icon)
        $cartCount = $this->CartModel->getCartItemCount($userId);

        // Leaderboard logic
        $today = date('Y-m-d');
        $todayLimit = intval($this->GameMappingModel->getTodayLeaderboardCount($today));
        $result = $this->PlayersModel->getTodayPlayers($today, $todayLimit, $userId);

        // 🔥 IMPORTANT: Get free tee item details from session
        $directItem = $session->get("direct_purchase_item");

        if (!$directItem) {
            return redirect()->to(base_url('/customproducts'));
        }

        $productDetails = $this->ProductModel
            ->asArray()
            ->where('pr_Id', (int) $directItem['pr_Id'])
            ->get()
            ->getRowArray();

        // Build cartItems array in same format as cart
        $cartItems = [
            [
                "pr_Id" => $directItem['pr_Id'],
                "pri_Id" => $directItem['pri_Id'],
                "design_Id" => $directItem['design_Id'],
                "cart_Quantity" => $directItem['quantity'],
                "cart_Size" => $directItem['size'],
                "cart_Price" => 0,
                "pr_Name" => $productDetails['pr_Name'],
                "pr_Code" => $productDetails['pr_Code']
            ]
        ];

        return view('common/header', [
            'cartCount' => $cartCount,
            'players' => $result['players'],
            'lastPlayer' => $result['lastPlayer']
        ])
            . view('orderdetailsforbuyfree', [
                'cartItems' => $cartItems,   // <-- 🔥 sending to view
                'totalAmount' => 0,
                'cust_Email' => $data['cust_Email'],
                'cust_Phone' => $data['cust_Phone']           // <-- free
            ])
            . view('common/footer')
            . view('pagescripts/orderdetailsjs');
    }

    public function placeFreeOrder()
    {
        $session = session();
        $userId = $session->get('user_id');
        $free_tee_lb_id = $session->get('free_tee_lb_id');
        // print_r($free_tee_lb_id);exit();

        if (!$userId) {
            return $this->response->setJSON([
                'status' => 'login_required'
            ]);
        }

        // Get FREE PURCHASE item
        $freeItem = $session->get("direct_purchase_item");
        // print_r($freeItem); exit();
        if (!$freeItem) {
            return $this->response->setJSON([
                "status" => "error",
                "message" => "No free product found"
            ]);
        }

        // ===========================
        // SAVE ADDRESS
        // ===========================

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
        // print_r($addressData); exit();
        $addressModel = new \App\Models\AddressModel();
        $add_Id = $addressModel->insert($addressData);

        $shippingAddress = implode(', ', array_filter($addressData));

        // ===========================
        // GENERATE ORDER NUMBER
        // ===========================
        $orderModel = new OrderDetailsModel();
        $lastOrder = $orderModel->orderBy('od_Id', 'DESC')->first();
        $nextNumber = $lastOrder ? ((int) substr($lastOrder['od_number'], -5) + 1) : 10000;

        $orderNumber = 'VOYC-' . date('Ymd') . '-' . $nextNumber;

        // $productDetails = $this->productModel->where('pr_Id', $freeItem['pr_Id'])->first();
        $productDetails = $this->ProductModel
            ->asArray()
            ->where('pr_Id', (int) $freeItem['pr_Id'])
            ->get()
            ->getRowArray();

        // print_r($productDetails);

        // exit();

        // ===========================
        // INSERT FREE ORDER ITEM
        // ===========================

        $orderItem = [
            "od_number" => $orderNumber,
            "cus_Id" => $userId,
            "add_Id" => $add_Id,
            "od_Shipping_Address" => $shippingAddress,
            "pr_Id" => $freeItem['pr_Id'],
            "pri_Id" => $freeItem['pri_Id'],
            "design_Id" => $freeItem['design_Id'],
            "od_Quantity" => 1,
            "od_Selling_Price" => 0,
            "od_Original_Price" => 0,
            "od_DiscountValue" => 100,
            "od_DiscountType" => "%",
            "od_Size" => $freeItem['size'],
            "pr_Code" => $productDetails['pr_Code'],
            "pr_Name" => $productDetails['pr_Name'],
            "od_Grand_Total" => 0
        ];
        // print_r($orderItem); exit();
        $this->orderModel->createOrderItem($orderItem);

        // ===========================
        // SEND EMAIL (Customer + Admin)
        // ===========================
        $email = \Config\Services::email();
        $logoUrl = base_url() . ASSET_PATH . "assets/img/logo-black.jpg";

        $productTable = "
    <table style='width:100%;border-collapse:collapse;margin-top:20px;font-size:14px;'>
        <tr>
            <th style='padding:8px;border:1px solid #ccc;background:#eee;'>Product</th>
            <th style='padding:8px;border:1px solid #ccc;background:#eee;'>Qty</th>
            <th style='padding:8px;border:1px solid #ccc;background:#eee;'>Price</th>
            <th style='padding:8px;border:1px solid #ccc;background:#eee;'>Total</th>
        </tr>
        <tr>
            <td style='padding:8px;border:1px solid #ccc;'>Free Customized T-Shirt</td>
            <td style='padding:8px;border:1px solid #ccc;'>{$freeItem['quantity']}</td>
            <td style='padding:8px;border:1px solid #ccc;'>₹0</td>
            <td style='padding:8px;border:1px solid #ccc;'>₹0</td>
        </tr>
    </table>
    ";

        $customerMsg = "
        <div style='text-align:center;'>
            <img src='{$logoUrl}' style='width:160px;margin-bottom:20px;'>
        </div>
        <p>Hello {$addressData['add_Name']},</p>
        <p>Your FREE T-shirt order is confirmed!</p>
        <p><b>Order No:</b> {$orderNumber}</p>
        {$productTable}
    ";

        // Customer email
        $email->setFrom('smartloungework@gmail.com', 'Voyc');
        $email->setTo($addressData['add_Email']);
        $email->setSubject("Order Confirmation - {$orderNumber}");
        $email->setMessage($customerMsg);
        $email->setMailType('html');
        $email->send();

        // Admin email
        $email->setTo("smartloungework@gmail.com");
        $email->setSubject("New FREE T-shirt Order - {$orderNumber}");
        $email->setMessage($customerMsg);
        $email->send();
        // Clear free purchase item session
        $session->remove("direct_purchase_item");

        // ===============================
        // CLEAR FREE TEE SESSION + UPDATE LEADERBOARD STATUS
        // ===============================

        // $lbId = $session->get('free_tee_lb_id');

        if (!empty($free_tee_lb_id)) {
            // print ("Updating leaderboard ID: " . $free_tee_lb_id);
            // exit();
            $this->UserleaderboardModel
                ->where('lb_Id', $free_tee_lb_id)
                ->where('cust_Id', $userId)
                ->set([
                    'lb_redeemed_status' => 2
                ])
                ->update();
        }


        // Remove eligibility flags
        $session->remove("eligible_for_free_tee");
        $session->remove("free_tee_lb_id");
        $session->remove("direct_purchase_item");

        return $this->response->setJSON([
            "status" => "success",
            "message" => "Order Placed Successfully at ₹0"
        ]);
    }

    // public function getShippingCharge()
    // {

    //     $rows = $db->table('common_table')
    //         ->whereIn('field', [
    //             'minimum_amount_for_shipping_charge',
    //             'shipping_charge'
    //         ])
    //         ->get()
    //         ->getResultArray();

    //     $data = [];
    //     foreach ($rows as $row) {
    //         $data[$row['field']] = (float) $row['value'];
    //     }

    //     return $this->response->setJSON([
    //         'status' => 'success',
    //         'data' => $data
    //     ]);
    // }

    // public function getShippingCharge()
    // {
    //     // $db = \Config\Database::connect();

    //     $shippingData = $this->db->table('common_table')
    //         ->whereIn('field', ['minimum_amount_for_shipping_charge', 'shipping_charge'])
    //         ->get()
    //         ->getResultArray();

    //     $shipping = [];
    //     foreach ($shippingData as $row) {
    //         $shipping[$row['field']] = $row['value'];
    //     }

    //     return json_encode($shipping);
    // }

}
