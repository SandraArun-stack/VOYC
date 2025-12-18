<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table = 'common_table';
    protected $primaryKey = 'common_table_Id';
    protected $allowedFields = ['common_table_Id', 'field', 'value'];



    public function getCustomizationCharges()
    {
        // Get all 3 customization charges in one query
        $fields = [
            'front_Customization_Price',
            'back_Customization_Price',
            'sleeve_Customization_Price',
            'shipping_charge',
            'minimum_amount_for_shipping_charge',
            'token_price_for_per_piece'
            // 'token_price'
        ];

        $result = $this->whereIn('field', $fields)->findAll();

        $charges = [
            'front_Customization_Price' => '',
            'back_Customization_Price' => '',
            'sleeve_Customization_Price' => '',
            'shipping_charge' => '',
            'minimum_amount_for_shipping_charge' => '',
            'token_price_for_per_piece' => ''
            // 'token_price' => ''
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