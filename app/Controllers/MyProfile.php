<?php
namespace App\Controllers;

use App\Models\MyProfileModel;
use App\Models\CartModel;
use CodeIgniter\Controller;

class MyProfile extends Controller
{
    protected $session;
    protected $request;
    protected $MyProfileModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->MyProfileModel = new MyProfileModel();
        $this->CartModel = new CartModel();
    }

    public function index()
    {
        $userId = $this->session->get('user_id');

        if (!$userId) {
            return redirect()->to(base_url('/'));
        }

        $data = [
            'user' => $this->MyProfileModel->getUserById($userId),
            'breadcrumb' => 'My Profile'
        ];
        $cartCount = $this->CartModel->getCartItemCount($userId);
        
        return view('common/header', ['cartCount' => $cartCount])
            . view('common/UserSideBar', $data)
            . view('myprofile', $data)
            . view('common/footer')
            . view('pagescripts/myprofilejs');
    }


    public function updateProfile()
    {
        $userId = $this->session->get('user_id');
        $data = [
            'cust_Name' => $this->request->getPost('cust_Name'),
            'cust_Email' => $this->request->getPost('cust_Email'),
            'cust_Phone' => $this->request->getPost('cust_Phone'),
            'cust_modifyon' => date('Y-m-d H:i:s')
        ];

        $updated = $this->MyProfileModel->update($userId, $data);

        return $this->response->setJSON(['success' => $updated ? true : false]);
    }

    public function changePassword()
    {
        $userId = $this->session->get('user_id');
        $current = trim($this->request->getPost('current_password'));
        $new = trim($this->request->getPost('new_password'));
        $confirm = trim($this->request->getPost('confirm_password'));

        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Session expired']);
        }

        $user = $this->MyProfileModel->find($userId);
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        $stored = trim($user['cust_Password']);
        $is_md5 = (strlen($stored) === 32 && preg_match('/^[a-f0-9]{32}$/i', $stored));
        $is_php_hash = strpos($stored, '$2y$') === 0 || strpos($stored, '$argon2') === 0;

        $valid = false;

        if ($is_php_hash && password_verify($current, $stored)) {
            $valid = true;
        } elseif ($is_md5 && md5($current) === $stored) {
            $valid = true;
        } elseif ($stored === $current) {
            $valid = true;
        }

        if (!$valid) {
            return $this->response->setJSON(['success' => false, 'message' => 'Current password incorrect']);
        }

        if ($new !== $confirm) {
            return $this->response->setJSON(['success' => false, 'message' => 'Passwords do not match']);
        }

        // Update with secure hash
        $newHash = password_hash($new, PASSWORD_DEFAULT);
        $updated = $this->MyProfileModel->update($userId, [
            'cust_Password' => $newHash,
            'cust_modifyon' => date('Y-m-d H:i:s')
        ]);

        if ($updated) {
            return $this->response->setJSON(['success' => true, 'message' => 'Password updated successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Update failed']);
        }
    }


}
