<?php
namespace App\Models;

use CodeIgniter\Model;

class CommonTableModel extends Model
{
    protected $table = 'common_table';
    protected $primaryKey = 'common_table_Id';
    protected $allowedFields = ['field', 'value'];

    public function getShippingCharge()
    {
        $row = $this->where('field', 'shipping_charge')->first();

        return (!empty($row) && is_numeric($row['value']))
            ? $row['value']
            : 500;
    }

}
