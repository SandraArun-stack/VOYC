<?php

namespace App\Controllers;

use Razorpay\Api\Api;

class PaymentController extends BaseController
{
    public function createRazorpayOrder()
    {
        $amount = (float) $this->request->getPost('amount');

        if ($amount <= 0) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid amount'
            ]);
        }

        $api = new Api(
            env('RAZORPAY_KEY_ID'),
            env('RAZORPAY_KEY_SECRET')
        );

        $order = $api->order->create([
            'receipt'  => 'voyc_' . time(),
            'amount'   => $amount * 100, // paise
            'currency' => 'INR'
        ]);

        return $this->response->setJSON([
            'status'   => 'success',
            'order_id'=> $order['id'],
            'amount'  => $order['amount'],
            'key'     => env('RAZORPAY_KEY_ID')
        ]);
    }
}
