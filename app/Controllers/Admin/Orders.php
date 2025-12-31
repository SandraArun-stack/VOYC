<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Admin\OrdersModel;

class Orders extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->input = \Config\Services::request();
        $this->OrdersModel = new \App\Models\Admin\OrdersModel();
    }
    public function index()
    {
        if ($this->session->get('ad_uid')) {
            $data = [];
            $orders = $this->OrdersModel->getDatatables();
            // print_r($orders);
            // exit;
            $template = view('Admin/common/header');
            $template .= view('Admin/common/leftmenu');
            $template .= view('Admin/orders', $data);
            $template .= view('Admin/common/footer');
            $template .= view('Admin/page_scripts/ordersjs');
            return $template;
        } else {
            if (!$this->session->get('ad_uid')) {
                return redirect()->to(base_url('admin'));
            }
        }

    }
    // Listing table data
    public function ajaxList()
    {
        $model = new \App\Models\Admin\OrdersModel();

        $orderColumnIndex = $this->request->getPost('order')[0]['column'] ?? 0;
        $orderDirection = $this->request->getPost('order')[0]['dir'] ?? 'desc';

        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $searchValue = $this->request->getPost('search')['value'];
        $statusFilter = $this->request->getPost('statusFilter');


        $columnMap = [
            null,
            'customer.cust_Name',
            'address.add_Email',
            'address.add_Phone',
            'product.pr_Code',
            'order_detail.od_Quantity',
            'order_detail.od_createdon',
            'order_detail.od_Status',
            null
        ];
        $orderBy = $columnMap[$orderColumnIndex] ?? 'order_detail.od_Id';

        // Get paginated data
        $data = $model->getDatatables($searchValue, $start, $length, $orderBy, $orderDirection,$statusFilter);

        $formattedData = [];
        foreach ($data['data'] as $row) {
            $itemCount = $model->getOrderItemCount($row->od_number);
            $address = $row->od_Shipping_Address ?? '';

            $email = '';
            if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $address, $matches)) {
                $email = $matches[0];
            }

            $phone = '';
            if (preg_match('/(\+?\d[\d\s\-()]{7,15})/', $address, $matches)) {
                $phone = trim($matches[0]);
            }

            $formattedData[] = [
                'od_Id' => $row->od_Id,  // ← ADD THIS
                'cust_Name' => $row->cust_Name ?? 'N/A',
                'od_number' => $row->od_number,
                'item_count' => $itemCount,
                'add_Email' => $row->add_Email ?? 'N/A',
                'add_Phone' => $row->add_Phone ?? 'N/A',
                'od_createdon' => !empty($row->od_createdon) ? date('d M Y', strtotime($row->od_createdon)) : 'N/A',
                'od_Status' => $model->getStatusByOrderNumber($row->od_number),
                'actions' => '<a href="' . base_url('admin/orders/view/' . $row->od_number) . '"><i class="fa fa-eye"></i></a>',
                'design_Id' => $row->design_Id ?? 0
            ];
        }

        return $this->response->setJSON([
            'draw' => intval($this->request->getPost('draw')),
            'recordsTotal' => $data['total'],        // total records without filtering
            'recordsFiltered' => $data['filtered'],  // total records after filtering
            'data' => $formattedData
        ]);
    }
    // for Labeling the Status

    private function getStatusLabel($status)
    {
        switch ($status) {
            case '1':
                return 'New';
            case '2':
                return 'Confirmed';
            case '3':
                return 'Packed';
            case '4':
                return 'Dispatched';
            default:
                return '';
        }
    }


    public function OrderView($od_number)
    {
        $model = new \App\Models\Admin\OrdersModel();

        if (!$this->session->get('ad_uid')) {
            return redirect()->to(base_url('admin'));
        }

        // Get rows by od_number
        $orderRows = $model->getOrderById($od_number);

        if (empty($orderRows)) {
            return "Order not found";
        }

        // Extract od_Id values
        $odIds = array_map(fn($row) => $row->od_Id, $orderRows);

        // Fetch complete order rows
        $orders = [];
        foreach ($odIds as $oid) {
            $ord = $model->getOrder($oid);
            if ($ord) {
                $orders[] = $ord;   // only push valid ones
            }
        }

        // Make sure at least one order exists
        if (empty($orders)) {
            return "Order not found or corrupted order entries.";
        }

        // First order provides customer/address
        $first = $orders[0];
        $status = $first->od_Status;
        $customer = $model->getCustomer($first->cus_Id);
        $address = $model->getAddress($first->add_Id);

       
        if (!$this->request->isAJAX()) {
            $data = [
                'od_number' => $od_number,
                'orders' => $orders,
                'customer' => $customer,
                'address' => $address,
                'status'    => $status 
                // 'designs' => $designs
            ];

            return view('Admin/common/header')
                . view('Admin/common/leftmenu')
                . view('Admin/order_view', $data)
                . view('Admin/common/footer')
                . view('Admin/page_scripts/orders_viewjs');
        }

        // AJAX
        return $this->response->setJSON([
            'status' => true,
            'data' => [
                'orders' => $orders,
                'customer' => $customer,
                'address' => $address,
                // 'designs' => $designs
            ]
        ]);
    }

    public function getDesignAjax()
    {
        $design_Id = $this->request->getPost('design_Id');

        $model = new \App\Models\Admin\OrdersModel();
        $design = $model->getCustomisedImage($design_Id);

        if (!$design) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No design found.'
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'data' => $design
        ]);
    }



    public function orderStatusUpdation($od_number)
    {
        $model = new \App\Models\Admin\OrdersModel();
        $status = $this->input->getPost('status');

        if ($this->request->isAJAX()) {
            $updation = $model->updateStatus($od_number,  $status);
            if ($updation) {
                if (!$status) {
                    return $this->response->setJSON([
                        'status' => false,
                        'message' => 'Missing The Status.'
                    ]);
                } 
                return $this->response->setJSON([
                    'status' => true,
                    'message' => 'Status Updated Successfully.'
                ]);
            }
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Status Updation Failed'
            ]);
        }
    }

}


