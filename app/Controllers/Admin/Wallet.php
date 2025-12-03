<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\WalletModel;
use App\Models\UserModel;

class Wallet extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->wallet = new WalletModel();
        $this->user = new UserModel();
    }

    public function index()
    {
        if (!$this->session->get('ad_uid'))
            return redirect()->to('admin');

        echo view('Admin/common/header');
        echo view('Admin/common/leftmenu');
        echo view('Admin/wallet');
        echo view('Admin/common/footer');
        echo view('Admin/page_scripts/walletjs');
    }

    public function ajaxList()
{
    $model = new \App\Models\Admin\WalletModel();

    $start  = $this->request->getPost('start');
    $length = $this->request->getPost('length');
    $search = $this->request->getPost('search')['value'] ?? '';

    $orderColumnIndex = $this->request->getPost('order')[0]['column'] ?? 0;
    $orderDir = $this->request->getPost('order')[0]['dir'] ?? 'DESC';

    $columns = [
        null,
        'customer.cust_Name',
        'uw_expiry',
        'uw_tokens',
        'uw_bonus_token',       
        'uw_purchased_token',  
        'uw_status'
    ];

    $orderBy = $columns[$orderColumnIndex] ?? 'uw_Id';

    $data = $model->getDatatables($search, $start, $length, $orderBy, $orderDir);

    return $this->response->setJSON([
        'draw'            => intval($this->request->getPost('draw')),
        'recordsTotal'    => $data['total'],
        'recordsFiltered' => $data['filtered'],
        'data'            => $data['data']
    ]);
}


}
