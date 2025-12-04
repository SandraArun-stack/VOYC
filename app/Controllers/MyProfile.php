<?php
namespace App\Controllers;

use App\Models\MyProfileModel;
use App\Models\CartModel;
use CodeIgniter\Controller;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\GameMappingModel;

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
        $this->PlayersModel = new PlayersModel();
        $this->GameMappingModel = new GameMappingModel();
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
            . view('common/UserSideBar', $data)
            . view('myprofile', $data)
            . view('common/footer')
            . view('pagescripts/myprofilejs');
    }

    public function updateProfile()
    {
        $userId = $this->session->get('user_id');
        $phone = $this->request->getPost('cust_Phone');

        // Normalize phone
        $phone = preg_replace('/\s+/', '', $phone);    // Remove spaces
        $phone = preg_replace('/[^0-9]/', '', $phone); // Keep only digits

        // Remove +91 or 91 prefix
        if (substr($phone, 0, 2) === "91" && strlen($phone) === 12) {
            $phone = substr($phone, 2); // Remove 91
        }

        // Remove leading 0 if length = 11
        if (substr($phone, 0, 1) === "0" && strlen($phone) === 11) {
            $phone = substr($phone, 1); // Remove 0
        }

        // FINAL VALIDATION: must be 10 digits and start with 6–9
        if (!preg_match('/^[6-9]\d{9}$/', $phone)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid Indian phone number.'
            ]);
        }

        // Save sanitized phone
        $data = [
            'cust_Name' => $this->request->getPost('cust_Name'),
            'cust_Email' => $this->request->getPost('cust_Email'),
            'cust_Phone' => $phone, // cleaned number
            'cust_modifyon' => date('Y-m-d H:i:s')
        ];

        $updated = $this->MyProfileModel->update($userId, $data);

        return $this->response->setJSON([
            'success' => $updated ? true : false,
            'message' => $updated ? 'Profile updated successfully' : 'Failed to update'
        ]);
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

        // Detect hash type
        $is_md5 = (strlen($stored) === 32 && preg_match('/^[a-f0-9]{32}$/i', $stored));
        $is_php_hash = (strpos($stored, '$2y$') === 0 || strpos($stored, '$argon2') === 0);

        $valid = false;

        // Verify current password
        if ($is_php_hash && password_verify($current, $stored)) {
            $valid = true;
        } elseif ($is_md5 && md5($current) === $stored) {
            $valid = true;
        } elseif ($stored === $current) { // plain text (old DB)
            $valid = true;
        }

        if (!$valid) {
            return $this->response->setJSON(['success' => false, 'message' => 'The Current Password is Incorrect.']);
        }

        if ($new !== $confirm) {
            return $this->response->setJSON(['success' => false, 'message' => 'Passwords do not match']);
        }

        // ✅ Store password as MD5 format (requested)
        $newHash = md5($new);

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
