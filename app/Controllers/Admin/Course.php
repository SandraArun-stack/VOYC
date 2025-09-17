<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Course extends BaseController
{
    public function __construct()
	{
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
		$this->CourseModel = new \App\Models\Admin\CourseModel();
	}

    public function index()
    {
       if (!$this->session->get('ad_uid')) {
			return redirect()->to(base_url('admin'));
		}
		//$getall['users'] = $this->staffModel->getAllStaff();
		$template = view('Admin/common/header');
		$template .= view('Admin/common/leftmenu');
		$template .= view('Admin/course_list');
		$template .= view('Admin/common/footer');
		return $template; 
    }

    public function ajaxList()
    {
        // later: return JSON for DataTables
    }

   public function addCourse()
    {
        $template = view('Admin/common/header');
		$template .= view('Admin/common/leftmenu');
		$template .= view('Admin/course_add');
		$template .= view('Admin/common/footer');
		return $template; 
    }
    public function saveCourse()
    {
        // handle save (new or update)
    }

    public function updateStatus()
    {
        // update course status (active/inactive)
    }

    public function deleteCourse($id)
    {
        // delete course by id
    }
}
