<?php

namespace App\Controllers;
use App\Models\NewProductModel;
use App\Models\HomeModel;
use App\Models\CartModel;
use App\Models\Admin\LeaderboardModel;
use App\Models\Admin\PlayersModel;
use App\Models\Admin\GameMappingModel;
use App\Models\Admin\UserSubscriptionsModel;
class Home extends BaseController
{
    protected $HomeModel;
    protected $categories;
    protected $session;
    protected $request;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->productdisplayModel = new HomeModel();
        $this->reviewModel = new NewProductModel();
        $this->CartModel = new CartModel();
        $this->LeaderboardModel = new LeaderboardModel();
        $this->PlayersModel = new PlayersModel();
        $this->GameMappingModel = new GameMappingModel();
        $this->UserSubscriptionsModel = new UserSubscriptionsModel();
    }

    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');

        if ($this->request->getGet('login_popup') == 1) {
            $session->setFlashdata('showLoginPopup', true);
        }
        $showLoginPopup = $session->getFlashdata('showLoginPopup');

        //get cart count
        $cartCount = $this->CartModel->getCartItemCount($userId);

        //get new product
        $newProductModel = new NewProductModel();
        $data['newPrdImg'] = $newProductModel->getNewPrdImage();
        $data['bestSeller'] = $newProductModel->getBestSeller();

        //leaderboard Count
        $today = date('Y-m-d');
        $todayLimit = $this->GameMappingModel->getTodayLeaderboardCount($today);
        $todayLimit = intval($todayLimit);

        $result = $this->PlayersModel->getTodayPlayers($today, $todayLimit, session()->get('user_id'));

        return view('common/header', [
            'cartCount' => $cartCount,
            'showLoginPopup' => $showLoginPopup,
            'players' => $result['players'],
            'lastPlayer' => $result['lastPlayer']
        ])
            . view('index', $data)
            . view('common/footer')
            . view('pagescripts/indexjs');
    }



    public function registerUser()
    {
        $fullName = ucwords(strtolower(trim($this->request->getPost('fullname'))));
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('reg_password');
        $confirm = $this->request->getPost('reg_confirm_password');
        $phone_number = $this->request->getPost('phone_number');
        $dob = $this->request->getPost('dob_cust');

        if (empty($fullName) || empty($email) || empty($password) || empty($confirm) || empty($phone_number)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Please Fill in All Required Fields.']);
        }

        if (!preg_match('/^[a-zA-Z ]+$/', $fullName)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Please Enter Name Correctly.']);
        }

        if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[A-Za-z]{2,}$/", $email)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid email format.']);
        }

        $phone_number = preg_replace('/[\s\-]/', '', $phone_number); // remove spaces/dashes

        if (strlen($phone_number) === 11 && str_starts_with($phone_number, '0')) {
            $phone_number = substr($phone_number, 1); // remove leading zero
        }

        if (!preg_match('/^(?:\+91|91)?[6-9]\d{9}$/', $phone_number)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Please Enter a Valid Phone Number.']);
        }


        if ($password !== $confirm) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Passwords do not Match.']);
        }

        if (
            strlen($password) < 8 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/\d/', $password) ||
            !preg_match('/[@$!%*#?&]/', $password)
        ) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Password Must be at Least 8 Characters and Include an Uppercase Letter, a Lowercase Letter, a Number, and a Special Character.'
            ]);
        }
        $password = md5($password);

        if (!empty($dob)) {
            $dobDate = strtotime($dob);
            $today = strtotime(date('Y-m-d'));

            if ($dobDate === false || $dobDate > $today) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Please Enter a Valid Date of Birth.'
                ]);
            }
        }
        $data = [
            'full_name' => $fullName,
            'email' => $email,
            'password' => $password,
            'phone_number' => $phone_number,
            'dob' => $dob
        ];

        $homeModel = new HomeModel();
        $result = $homeModel->registerUser($data);

        return $this->response->setJSON($result);
    }
    public function loginUser()
    {
        $email = $this->request->getPost('login_email');
        $password = md5($this->request->getPost('login_password'));
        $captchaResponse = $this->request->getPost('g-recaptcha-response');

        if (empty($email) || empty($password)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Please Fill in All Required Fields.']);
        }

        $secretKey = '6Le-VXcrAAAAAKSXShzC3A8GxolszKELxQ1S-9q9';
        $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captchaResponse}");
        $responseData = json_decode($verifyResponse);

        if (!$responseData->success) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Captcha verification failed.']);
        }

        $data = [
            'email' => $email,
            'password' => $password,
        ];

        $homeModel = new HomeModel();
        $result = $homeModel->loginUser_Model($data);

        if ($result['status'] === 'success') {
            $session = session();
            $session->set([
                'user_id' => $result['user']['cust_Id'],
                'user_email' => $result['user']['cust_Email'],
                'user_name' => $result['user']['cust_Name'],
                'isLoggedIn' => true
            ]);

        }


        return $this->response->setJSON($result);
    }
    public function setFreeTeeSession()
    {
        $session = session();

        $coupon = $this->request->getPost('coupon');
        $lbId = $this->request->getPost('lb_id');

        // Set eligibility
        $session->set('eligible_for_free_tee', true);
        // $session->set('free_tee_coupon', $coupon);
        $session->set('free_tee_lb_id', $lbId);

        return $this->response->setJSON(['status' => 'success']);
    }
    public function logoutUser()
    {
        // echo "hii";exit();
        $session = session();
        $session->destroy();

        return $this->response->setJSON(['status' => 'success', 'message' => 'Logged out successfully']);
    }




}

