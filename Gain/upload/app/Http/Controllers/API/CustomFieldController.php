<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomField;
use Illuminate\Support\Facades\Lang;
use DB;

class CustomFieldController extends Controller
{

    public function serviceFieldData(Request $request)
    {
        $serviceFieldData = CustomField::getServiceFieldData($request);

        $allServiceFieldData = $serviceFieldData['data'];

        foreach ($allServiceFieldData as $value) {
            $value['field_type'] = Lang::get('lang.' . $value['field_type']);
            $value['is_enable'] == 1 ? $value['is_enable'] = 'Enabled' : $value['is_enable'] = 'Disabled';
            $value['show_in_table'] == 1 ? $value['show_in_table'] = 'Yes' : $value['show_in_table'] = 'No';
        }
        return ['datarows' => $allServiceFieldData, 'count' => $serviceFieldData['count']];
    }

    public function bookingFieldData(Request $request)
    {
        $bookingFieldData = CustomField::getBookingFieldData($request);

        $allBookingFieldData = $bookingFieldData['data'];

        foreach ($allBookingFieldData as $value) {
            $value['field_type'] = Lang::get('lang.' . $value['field_type']);
            $value['is_enable'] == 1 ? $value['is_enable'] = 'Enabled' : $value['is_enable'] = 'Disabled';
            $value['show_in_table'] == 1 ? $value['show_in_table'] = 'Yes' : $value['show_in_table'] = 'No';
        }
        return ['datarows' => $allBookingFieldData, 'count' => $bookingFieldData['count']];
    }

    public function save(Request $request)
    {
        $fieldType = $request->fieldType;
        $options = serialize($request->options);
        $label = $request->label;
        $tableName = $request->tableName;
        $visibleToClient = 0;
        $showTable = 0;
        $status = 0;

        if ($request->enableField) {
            $status = 1;
        }

        if ($request->showTable) {
            $showTable = 1;
        }

        if ($request->visibleToClient) {
            $visibleToClient = 1;
        }

        CustomField::create([
            'field_type' => $fieldType,
            'options' => $options,
            'label' => $label,
            'table_name' => $tableName,
            'is_enable' => $status,
            'service_id' => $request->serviceId,
            'show_in_table' => $showTable,
            'visible_in_client' => $visibleToClient
        ]);

        $response = [
            'message' => Lang::get('lang.custom_field_save_successfully')
        ];
        return response()->json($response, 201);


    }

    public function serviceFiledEdit($id)
    {
        $data = CustomField::where('id', $id)->first();
        $data->options = unserialize($data->options);
        return $data;
    }

    public function bookingFiledEdit($id)
    {
        $data = CustomField::where('id', $id)->first();
        $data->options = unserialize($data->options);
        return $data;
    }

    public function update(Request $request, $id)
    {
        $options = serialize($request->options);
        $label = $request->label;
        $visibleToClient = 0;
        $status = 0;
        $showTable = 0;

        if ($request->showTable) {
            $showTable = 1;
        }
        if ($request->enableField) {
            $status = 1;
        }
        if ($request->visibleToClient) {
            $visibleToClient = 1;
        }

        CustomField::where('id', $id)->update([
            'options' => $options,
            'label' => $label,
            'is_enable' => $status,
            'show_in_table' => $showTable,
            'service_id' => $request->serviceId,
            'visible_in_client' => $visibleToClient
        ]);

        $response = [
            'message' => Lang::get('lang.custom_field_update_successfully')
        ];

        return response()->json($response, 200);
    }

    public function getServiceField(Request $request)
    {

        $tableName = $request->table_name;
        $serviceFields = CustomField::where('is_enable', 1)->where('table_name', $tableName)->get();

        foreach ($serviceFields as $serviceField) {
            $serviceField->options = unserialize($serviceField->options);
        }

        return $serviceFields;
    }

    public function getClientBookingField()
    {

        $bookingFields = CustomField::where('is_enable', 1)->where('table_name', 'booking')->where('visible_in_client', 1)->get();

        foreach ($bookingFields as $fields) {
            $fields->options = unserialize($fields->options);
        }
        return $bookingFields;
    }

    public function deleteSettingservice($id)
    {
        $customfiledisexists = CustomField::deletecustomService($id);
        if ($customfiledisexists == null) {
            CustomField::where('id', $id)->delete();
            $response = [
                'message' => Lang::get('lang.service_delete_custom_filed')
            ];
            return response()->json($response, 201);

        } else {

            $response = [
                'message' => Lang::get('lang.filed_in_use')
            ];
            return response()->json($response, 200);
        }
    }

    public function deleteSettingbooking($id)
    {
        $customfiledisexists = CustomField::deletecustomBooking($id);
        if ($customfiledisexists == null) {
            CustomField::where('id', $id)->delete();
            $response = [
                'message' => Lang::get('lang.booking_delete_custom_filed')
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'message' => Lang::get('lang.filed_in_use')
            ];
            return response()->json($response, 200);
        }
    }
}