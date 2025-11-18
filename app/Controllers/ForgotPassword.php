<?php
namespace App\Controllers;

use App\Models\ForgotPasswordModel;
use CodeIgniter\Controller;

class ForgotPassword extends Controller
{
    protected $session;
    protected $request;
    protected $ForgotPasswordModel;
    protected $email;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->ForgotPasswordModel = new ForgotPasswordModel();
        $this->email = \Config\Services::email();
    }
    public function forgotPassword()
    {
        $email = $this->request->getPost('forgot_email');

        if (empty($email)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Email is required']);
        }

        // ✅ Only fetch users with cust_Status = 1
        $user = $this->ForgotPasswordModel->getActiveUserByEmail($email);

        if (!$user) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Email not found or account inactive']);
        }

        // Generate token (1 hour expiry)
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Save token in DB
        $this->ForgotPasswordModel->update($user['cust_Id'], [
            'reset_token' => $token,
            'reset_token_expiry' => $expiry
        ]);

        // Build reset link
        $resetUrl = base_url('resetPassword/' . $token);

        // Email content
        $subject = 'Password Reset Request';

        $logoUrl = base_url(ASSET_PATH . 'assets/img/logo-black.jpg');

        $message = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Password Reset</title>
                <style>
                    /* Basic email reset styles */
                    body {
                        font-family: Arial, Helvetica, sans-serif;
                        background-color: #f8f9fa;
                        color: #333;
                        margin: 0;
                        padding: 0;
                    }
                    .email-container {
                        max-width: 600px;
                        background: #ffffff;
                        margin: 30px auto;
                        border-radius: 8px;
                        overflow: hidden;
                        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                    }
                    .email-header {
                        text-align: center;
                        background-color: #ffffff;
                        padding: 20px;
                    }
                    .email-header img {
                        max-width: 150px;
                        height: auto;
                    }
                    .email-body {
                        padding: 25px;
                        text-align: left;
                    }
                    .email-body p {
                        line-height: 1.6;
                        font-size: 15px;
                        margin-bottom: 15px;
                    }
                    .btn {
                        display: inline-block;
                        background-color: #0d6efd;
                        color: #fff !important;
                        text-decoration: none;
                        padding: 10px 20px;
                        border-radius: 6px;
                        font-weight: 600;
                    }
                    .btn:hover {
                        background-color: #0b5ed7;
                    }
                    .email-footer {
                        text-align: center;
                        padding: 15px;
                        font-size: 13px;
                        color: #888;
                        background-color: #f8f9fa;
                    }
                    @media only screen and (max-width: 600px) {
                        .email-body { padding: 15px; }
                        .email-header img { max-width: 120px; }
                    }
                </style>
            </head>
            <body>
                <div class="email-container">
                    <div class="email-header">
                        <img src="' . $logoUrl . '" alt="Voyc Logo">
                    </div>
                    <div class="email-body">
                        <p>Hello ' . htmlspecialchars($user['cust_Name']) . ',</p>
                        <p>We received a request to reset your password. Click the button below to reset it:</p>
                        <p style="text-align:center;">
                            <a href="' . $resetUrl . '" class="btn" target="_blank">Reset Password</a>
                        </p>
                        <p>This link will expire in <strong>1 hour</strong>.</p>
                        <p>If you did not request this, you can safely ignore this email.</p>
                    </div>
                    <div class="email-footer">
                        &copy; ' . date('Y') . ' Voyc. All rights reserved.
                    </div>
                </div>
            </body>
            </html>';

        // Send email
        $this->email->setTo($email);
        $this->email->setFrom('smartloungework@gmail.com', 'Voyc');
        $this->email->setSubject($subject);
        $this->email->setMessage($message);

        if ($this->email->send()) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Reset link sent to your email']);
        } else {
            $error = $this->email->printDebugger(['headers']);
            log_message('error', 'Email send failed: ' . $error); // log in writable/logs
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to send email',
                'debug' => $error
            ]);
        }

    }


    // Step 2: Display reset form
    public function resetPassword($token)
    {
        $user = $this->ForgotPasswordModel->where('reset_token', $token)->first();

        if (!$user || strtotime($user['reset_token_expiry']) < time()) {
            return view('reset_expired');
        }

        return view('reset_password_form', ['token' => $token]);
    }

    // Step 3: Update password
    // public function updatePassword()
    // {
    //     $token = $this->request->getPost('token');
    //     $newPassword = md5($this->request->getPost('new_password'));
    //     $user = $this->ForgotPasswordModel->where('reset_token', $token)->first();

    //     if (!$user) {
    //          return view('reset_expired');
    //     }

    //     if (strtotime($user['reset_token_expiry']) < time()) {
    //         return view('reset_expired');
    //     }


    //     $this->ForgotPasswordModel->update($user['cust_Id'], [
    //         'cust_Password' => $newPassword,
    //         'reset_token' => null,
    //         'reset_token_expiry' => null
    //     ]);

    //     return redirect()->to('/')->with('success', 'Password updated successfully');
    // }


    public function updatePassword()
    {
        $token = $this->request->getPost('token');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Check password match
        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Passwords do not match. Please try again.');
        }

        // Hash password
        $hashedPassword = md5($newPassword);

        // Find user via token
        $user = $this->ForgotPasswordModel->where('reset_token', $token)->first();

        if (!$user) {
            return view('reset_expired');
        }

        if (strtotime($user['reset_token_expiry']) < time()) {
            return view('reset_expired');
        }

        // Update password
        $this->ForgotPasswordModel->update($user['cust_Id'], [
            'cust_Password' => $hashedPassword,
            'reset_token' => null,
            'reset_token_expiry' => null
        ]);

        return redirect()->to('/')->with('success', 'Password updated successfully');
    }

}
