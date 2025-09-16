<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class CourseModel extends Model
{
    
    protected $table = 'courses';
    protected $primaryKey = 'course_id';
    protected $allowedFields = ['course_name', 'description', 'course_duration'];
}
