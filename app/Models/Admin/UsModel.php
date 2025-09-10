<?php 
namespace App\Models\Admin;

use CodeIgniter\Model;

class UsModel extends Model {

	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function getLoginAccount($email, $password) {
		// echo "select * from user where us_Email = '".$email."' and us_Password = '".$password."'";exit();
		return $this->db->query("select * from user where us_Email = '".$email."' and us_Password = '".$password."'")->getRow();

	}
}
?>
