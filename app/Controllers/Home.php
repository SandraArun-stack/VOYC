<?php

namespace App\Controllers;
use App\Models\NewProductModel;
use App\Models\HomeModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Home extends BaseController
{
    protected $HomeModel;
    protected $categories;
    protected $session;
    protected $request;
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->productdisplayModel = new HomeModel();
        $this->reviewModel = new NewProductModel();
    }
    public function index()
    {
        $newProductModel = new NewProductModel();
        $data['newPrdImg'] = $newProductModel->getNewPrdImage();
        $data['bestSeller'] = $newProductModel->getBestSeller();
        return view('common/header')
            . view('index', $data)
            . view('common/footer')
            . view('pagescripts/indexjs');
    }
    public function registerUser()
    {
        $fullName = ucwords(strtolower(trim($this->request->getPost('fullname'))));
        $email = $this->request->getPost('email');
        $password = md5($this->request->getPost('reg_password'));
        $confirm = md5($this->request->getPost('reg_confirm_password'));

        if (empty($fullName) || empty($email) || empty($password) || empty($confirm)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Please Fill in All Required Fields.']);
        }

        if (!preg_match('/^[a-zA-Z ]+$/', $fullName)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Please Enter Name Correctly.']);
        }

        if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[A-Za-z]{2,}$/", $email)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid email format.']);
        }

        if ($password !== $confirm) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Passwords do not Match.']);
        }

        if (strlen($password) < 8) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Password must be at least 8 Characters Long.']);
        }
        $data = [
            'full_name' => $fullName,
            'email' => $email,
            'password' => $password,
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
    public function logoutUser()
    {
        // echo "hii";exit();
        $session = session();
        $session->destroy();

        return $this->response->setJSON(['status' => 'success', 'message' => 'Logged out successfully']);
    }

}

