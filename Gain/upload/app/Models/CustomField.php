<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    protected $fillable = ['field_type','options', 'label','table_name', 'service_id', 'is_enable','show_in_table','visible_in_client'];

    // Custom Service Delete
    public static function deletecustomService($id)
    {
        return CustomField::Join('custom_data_fields', 'custom_data_fields.custom_fields_id', '=', 'custom_fields.id')->where('custom_fields_id', $id)->first();
    }
    //Custom Booking Delete
    public static function deletecustomBooking($id)
    {
        return CustomField::Join('custom_data_fields', 'custom_data_fields.custom_fields_id', '=', 'custom_fields.id')->where('custom_fields_id', $id)->first();
    }

    public static function getServiceFieldData($request){
        $searchValue = $request->searchValue;

        $query = CustomField::select('*')->where('table_name', 'service');


        if (!empty($searchValue)) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('custom_fields.field_type', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('custom_fields.label', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('custom_fields.is_enable', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('custom_fields.show_in_table', 'LIKE', '%' . $searchValue . '%');
            });
        }

        if (empty($requestType)) {
            $count = $query->get()->count();
            $allData = $query->get();

            return ["data" => $allData,'count' => $count];

          } else {
            return $query->orderBy($request->columnKey, $request->columnSortedBy)->get();
        }
    }


    public static function getBookingFieldData($request){
        $searchValue = $request->searchValue;

        $query = CustomField::select('*')->where('table_name', 'booking');


        if (!empty($searchValue)) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('custom_fields.field_type', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('custom_fields.label', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('custom_fields.is_enable', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('custom_fields.show_in_table', 'LIKE', '%' . $searchValue . '%');
            });
        }

        if (empty($requestType)) {
            $count = $query->get()->count();
            $allData = $query->get();

            return ["data" => $allData,'count' => $count];

        } else {
            return $query->orderBy($request->columnKey, $request->columnSortedBy)->get();
        }
    }
}


