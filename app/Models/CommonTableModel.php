<?php
namespace App\Models;

use CodeIgniter\Model;

class CommonTableModel extends Model
{
    protected $table = 'common_table';
    protected $primaryKey = 'common_table_Id';
    protected $allowedFields = ['field', 'value'];

    public function get_minimum_amount_for_shipping_charge()
    {
        $row = $this->where('field', 'minimum_amount_for_shipping_charge')->first();

        return (!empty($row) && is_numeric($row['value']))
            ? $row['value']
            : 500;
    }

}
