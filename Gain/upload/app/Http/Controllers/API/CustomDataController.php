<?php

namespace App\Http\Controllers\API;

use App\Libraries\AllSettingsFormat;
use App\Models\CustomField;
use App\Http\Controllers\Controller;
use App\Models\CustomDataField;

class CustomDataController extends Controller
{

    public function insertUpdateCustomFieldData($tableName, $dataId, $options, $update = true)
    {
        if (isset($options) && !empty($options)) {
            foreach ($options as $key => $option) {
                if ($key != 'check') {

                    if ($update) $this->updateInsert($tableName, $dataId, $key, $option);
                    else $this->insertCustomData($tableName, $dataId, $key, $option);

                } else {
                    $checkValues = $option;
                    $checks = CustomField::where('table_name', $tableName)->where('show_in_table', 1)->where('is_enable', 1)->where('field_type', 'checkbox')->get();

                    foreach ($checks as $check) {

                        $fieldId = $check->id;

                        $checkboxes = array_filter($checkValues, function ($element) use ($fieldId) {

                            if (strpos($element, strval($fieldId)) === 0) {
                                return $element;
                            }
                        });

                        $checkboxes = array_map(function ($element) use ($fieldId) {
                            return substr($element, strlen(strval($fieldId)));
                        }, $checkboxes);

                        $checkboxes = implode(",", $checkboxes);
                        if ($checkboxes != '') {

                            if ($update) $this->updateInsert($tableName, $dataId, $fieldId, $checkboxes);
                            else $this->insertCustomData($tableName, $dataId, $fieldId, $checkboxes);
                        }
                    }
                }
            }
        }
    }

    public function insertCustomData($tableName, $dataId, $key, $option)
    {

        $customFieldsDetails = [
            'table_name' => $tableName,
            'data_id' => $dataId,
            'custom_fields_id' => $key,
            'value' => $option
        ];

        CustomDataField::create($customFieldsDetails);
    }

    public function updateInsert($tableName, $dataId, $key, $option)
    {

        $exists = CustomDataField::where('table_name', $tableName)->where('data_id', $dataId)->where('custom_fields_id', $key)->exists();
        if ($exists) {
            CustomDataField::where('table_name', $tableName)->where('data_id', $dataId)->where('custom_fields_id', $key)->update(['value' => $option]);

        } else {
            $this->insertCustomData($tableName, $dataId, $key, $option);
        }
    }

    public function getCustomFieldData($tableName, $id)
    {
        $customFields = CustomDataField::join('custom_fields', 'custom_fields.id', 'custom_data_fields.custom_fields_id')
            ->where('custom_data_fields.table_name', $tableName)
            ->where('custom_data_fields.data_id', $id)
            ->select('custom_data_fields.*', 'custom_fields.field_type')
            ->get();

        $options = [];
        $dateId = [];
        $checkboxes = [];
        foreach ($customFields as $fields) {

            $id = $fields->id;
            $customFieldsId = $fields->custom_fields_id;

            if ($fields->field_type == 'date') array_push($dateId, $fields->custom_fields_id);

            if ($fields->field_type == 'checkbox') {
                $values = explode(',', $fields->value);

                $checkbox = array_map(function ($element) use ($customFieldsId) {
                    return $customFieldsId . $element;
                }, $values);

                foreach ($checkbox as $check) {
                    array_push($checkboxes, $check);
                }
            } else {
                $value = $fields->value;
                $options[$customFieldsId] = $value;
            }
        }

        $options['check'] = $checkboxes;

        return ['options' => $options, 'dateId' => $dateId];
    }

    public function deleteCustomFieldsRecords($tableName, $recordId)
    {
        CustomDataField::where('table_name', $tableName)->where('data_id', $recordId)->delete();
    }

    public function showCustomDataInDataTable($tableName, $data)
    {
        $fields = CustomField::where('table_name', $tableName)->where('show_in_table', 1)->where('is_enable', 1)->get();
        $dateId = [];
        $columnName = [];
        $settingApp = new AllSettingsFormat;

        foreach ($data as $item) {

            foreach ($fields as $field) {
                $value = CustomDataField::where('data_id', $item->id)->where('table_name', $tableName)->where('custom_fields_id', $field->id)->first();
                if ($value != null) {

                    if ($field->field_type == 'number') $item[$field->label] = $settingApp->getCurrency($settingApp->thousandSep($value->value));
                    else $item[$field->label] = $value->value;

                    $item['custom_field_id'] = $value->id;

                } else {
                    $item[$field->label] = '';
                    $item['custom_field_id'] = 0;
                }
            }
        }

        foreach ($fields as $field) {
            $columnName[$field->id] = $field->label;

            if ($field->field_type == 'date') {
                array_push($dateId, $field->id);
            }
        }

        return ['columnName' => $columnName, 'data' => $data, 'customDateId' => $dateId];
    }
}
