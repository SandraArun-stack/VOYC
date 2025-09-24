<?php namespace App\Models;

use CodeIgniter\Model;

class NewProductModel extends Model
{
	protected $table = 'address';
    protected $primaryKey = 'add_Id';
    protected $allowedFields = [
        'add_Name', 'add_Email'
    ];
	
	
}

