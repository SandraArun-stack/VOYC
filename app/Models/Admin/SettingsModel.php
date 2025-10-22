<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table = 'common_table';
    protected $primaryKey = 'common_table_Id';
    protected $allowedFields = ['common_table_Id', 'field', 'value'];

    // Fetch the customization charge row
    public function getCustomizationCharge()
    {
        return $this->where('field', 'customization_charge')->first();  // This returns an object
    }

    // Update or insert customization charge value
    public function updateCustomizationCharge($value)
    {
        $existing = $this->where('field', 'customization_charge')->first();
        if ($existing) {
            return $this->update($existing['common_table_Id'], ['value' => $value]);
        } else {
            return $this->insert(['field' => 'customization_charge', 'value' => $value]);
        }
    }
}

?>