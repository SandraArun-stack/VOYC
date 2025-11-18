<?php
namespace App\Controllers;

use App\Models\ContactModel;

use App\Models\CartModel;
use CodeIgniter\Controller;

class Contact extends Controller
{
    protected $session;
    protected $request;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->CartModel = new CartModel();
    }

    public function index()
    {
        $session = session();
    $userId  = $session->get('user_id');
    $cartCount = $this->CartModel->getCartItemCount($userId);

        return view('common/header', ['cartCount' => $cartCount])
            . view('contact')
            . view('common/footer')
            . view('pagescripts/contactjs');
    }

    public function submit()
    {
        helper(['form']);

        $validationRules = [
            'fullname' => 'required|min_length[3]|max_length[50]',
            'email' => 'required|valid_email',
            'contact_no' => [
                'rules' => 'required|regex_match[/^(0[6-9]\d{9}|[6-9]\d{9})$/]',
                'errors' => [
                    'regex_match' => 'Please enter a valid Indian mobile number: 10 digits starting with 6-9 or 11 digits starting with 0.'
                ]
            ],
            'message' => 'required|min_length[5]'
        ];

        // Validate without URL
        if (!$this->validate($validationRules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Save to DB
        $contactModel = new \App\Models\ContactModel();
        $data = [
            'fullname' => $this->request->getPost('fullname'),
            'email' => $this->request->getPost('email'),
            'contact_no' => $this->request->getPost('contact_no'),
            'message' => $this->request->getPost('message'),
            'submitted_at' => date('Y-m-d H:i:s'),
        ];
        $contactModel->insert($data);

        $email = \Config\Services::email();

        $logoUrl = base_url() . ASSET_PATH . "assets/img/logo-black.jpg";

        $emailHeader = "
            <div style='text-align:center; margin-bottom:20px;'>
                <img src='{$logoUrl}' style='width:160px;'>
            </div>
            ";

        $adminMessage = "
            <div style='font-family:Arial, sans-serif; color:#333; line-height:1.6; max-width:600px; margin:0 auto; border:1px solid #e0e0e0; padding:20px; background-color:#f9f9f9;'>
                {$emailHeader}
                <h2 style='color:#222; font-size:20px; border-bottom:1px solid #ccc; padding-bottom:10px;'>New Contact Enquiry Received</h2>

                <p><strong>Name:</strong> {$data['fullname']}</p>
                <p><strong>Email:</strong> {$data['email']}</p>
                <p><strong>Phone:</strong> {$data['contact_no']}</p>
                <p><strong>Message:</strong></p>
                <p style='background-color:#fff; padding:10px; border:1px solid #ddd; border-radius:5px;'>{$data['message']}</p>

                <p style='font-size:12px; color:#777; margin-top:20px;'>Submitted At: " . date('d M Y, h:i A') . "</p>

                <p style='margin-top:30px; font-size:14px;'>Regards,<br><strong>Team Voyc</strong></p>
            </div>
            ";


        $email->setFrom('smartloungework@gmail.com', 'Website Contact');
        $email->setTo('smartloungework@gmail.com'); // Admin email
        $email->setSubject('New Contact Enquiry Received');
        $email->setMessage($adminMessage);
        $email->setMailType('html');
        $email->send();


        return $this->response->setJSON([
            'status' => 'success'
        ]);
    }

}
