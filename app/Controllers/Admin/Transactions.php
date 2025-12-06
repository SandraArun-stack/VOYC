<?php
namespace App\Controllers\Admin;
 
use App\Controllers\BaseController;
use App\Models\Admin\SubscriptionModel;
use App\Models\Admin\CustomerModel;
use App\Models\Admin\TransactionsModel;
 
class Transactions extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->model = new SubscriptionModel();
        $this->customerModel = new CustomerModel(); 
        $this->transactionModel = new TransactionsModel();
    }
    public function index()
    {
        if (!$this->session->get('ad_uid')) {
            return redirect()->to('admin');
        }
        $template = view('Admin/common/header');
		$template .= view('Admin/common/leftmenu');
        $template .= view('Admin/transactionslist');
		$template .= view('Admin/common/footer');
		$template .= view('Admin/page_scripts/transactionsjs');
        return $template;
    }
    public function ajaxList()
    {
        $model = new TransactionsModel();

        $data      = $model->getDatatables();
        $total     = $model->countAll();
        $filtered  = $model->countFiltered();

        foreach ($data as &$row) {
            $row['initiated_at'] = !empty($row['initiated_at'])
                ? date('d-m-Y', strtotime($row['initiated_at']))
                : 'N/A';

            $row['cust_Name'] = !empty($row['cust_Name'])
                ? ucwords(strtolower($row['cust_Name']))
                : 'N/A';

            $row['payment_method'] = $row['payment_method'] ?? 'N/A';
            $row['transaction_amount'] = $row['transaction_amount'] ?? '0';

            switch ($row['transaction_status']) {
                case 'initiated':
                    $row['transaction_status'] = '<span class="badge badge-secondary">Initiated</span>';
                    break;

                case 'success':
                    $row['transaction_status'] = '<span class="badge badge-success">Success</span>';
                    break;

                case 'failed':
                    $row['transaction_status'] = '<span class="badge badge-danger">Failed</span>';
                    break;

                case 'refund':
                    $row['transaction_status'] = '<span class="badge badge-info">Refund</span>';
                    break;

                default:
                    $row['transaction_status'] = '<span class="badge badge-secondary">Unknown</span>';
            }
            $row['actions'] = '
                <a href="' . base_url('admin/transactions/view/' . $row['transaction_Id']) . '" 
                class="" title="View">
                    <i class="fa fa-eye"></i>
                </a>
            ';
        }
        return $this->response->setJSON([
            'draw'            => intval($this->request->getPost('draw')),
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data
        ]);
    }
    public function view($transactionId)
    {
        $model = new TransactionsModel();
        $tokenModel = new \App\Models\Admin\TokenTopupModel(); // make sure this model exists

        if (!$this->session->get('ad_uid')) {
            return redirect()->to(base_url('admin'));
        }

        // Get transaction with customer & subscription info
        $transaction = $model
            ->select('transactions.*, customer.cust_Name, sp.sp_plan_name, sp.sp_amount')
            ->join('customer', 'customer.cust_Id = transactions.cust_Id', 'left')
            ->join('subscription_plan sp', 'sp.sp_Id = transactions.sp_Id', 'left')
            ->where('transaction_Id', $transactionId)
            ->first();

        if (empty($transaction)) {
            return "Transaction not found";
        }

        // Sum all token counts for the customer
        $transaction['tt_count'] = 0;
        if (!empty($transaction['cust_Id'])) {
            $transaction['tt_count'] = $tokenModel
                ->selectSum('tt_count')
                ->where('cust_Id', $transaction['cust_Id'])
                ->first()['tt_count'] ?? 0;
        }

        $data = [
            'transaction' => $transaction
        ];

        $template = view('Admin/common/header');
        $template .= view('Admin/common/leftmenu');
        $template .= view('Admin/transactions_view', $data);
        $template .= view('Admin/common/footer');
        $template .= view('Admin/page_scripts/transactionsjs');
        return $template;
    }




}