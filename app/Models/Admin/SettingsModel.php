<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table = 'common_table';
    protected $primaryKey = 'common_table_Id';
    protected $allowedFields = ['common_table_Id', 'field', 'value'];

    // public function getCustomizationCharge()
    // {
    //     return $this->where('field', 'customization_charge')->first();  // This returns an object
    // }

    public function getCustomizationCharges()
    {
        // Get all 3 customization charges in one query
        $fields = [
            'front_Customization_Price',
            'back_Customization_Price',
            'sleeve_Customization_Price'
        ];

        $result = $this->whereIn('field', $fields)->findAll();

        $charges = [
            'front_Customization_Price' => '',
            'back_Customization_Price' => '',
            'sleeve_Customization_Price' => ''
        ];

        foreach ($result as $row) {
            $charges[$row['field']] = $row['value'];
        }

        return $charges;
    }

    public function updateCustomizationCharge($field, $value)
    {
        $existing = $this->where('field', $field)->first();

        if ($existing) {
            return $this->update($existing['common_table_Id'], ['value' => $value]);
        } else {
            return $this->insert(['field' => $field, 'value' => $value]);
        }
    }

}

?>