<?php

namespace App\Models;

use App\Libraries\AllSettingsFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Booking extends Model
{
    protected $fillable = [
        'service_id',
        'user_id',
        'phone_number',
        'status',
        'booking_date',
        'booking_time',
        'quantity',
        'comment',
        'adult',
        'children',
        'booking_bill',
        'calendar_event_id',
    ];

    
    public static function getBookingList($request, $businessType)
    {
        $searchValue = $request->searchValue;
        $filtersData = $request->filtersData;
        $query = Booking::join('users', 'bookings.user_id', '=', 'users.id')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->join('payments', 'bookings.id', '=', 'payments.booking_id')
            ->where('services.business_type', '=', $businessType)
            ->select('bookings.*', 'services.title', 'services.price',DB::raw('concat(users.first_name," ",users.last_name) as full_name'), 'payments.paid_amount');

        //Search Filter
        if (!empty($searchValue)) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('users.first_name', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('users.last_name', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('services.title', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('bookings.booking_date', 'LIKE', '%' . $searchValue . '%');
            });
        }
        //DropDown Filter For Department
        if (!empty($filtersData)) {
            foreach ($filtersData as $singleFilter) {
                if (array_key_exists('key', $singleFilter) && $singleFilter['key'] == "payment_status" && $singleFilter['value'] == 1) {
                    $query->whereRaw('bookings.booking_bill-payments.paid_amount > 0');
                } else if (array_key_exists('key', $singleFilter) && $singleFilter['key'] == "payment_status" && $singleFilter['value'] == 2) {
                    //$query->where('leaves.status', $singleFilter['value']);
                    $query->whereRaw('bookings.booking_bill-payments.paid_amount = 0');
                } else if (array_key_exists('key', $singleFilter) && $singleFilter['key'] == "status" && $singleFilter['value'] == 1) {
                    $query->where('bookings.status', 'confirmed');
                } else if (array_key_exists('key', $singleFilter) && $singleFilter['key'] == "status" && $singleFilter['value'] == 2) {
                    $query->where('bookings.status', 'pending');
                } else if (array_key_exists('key', $singleFilter) && $singleFilter['key'] == "status" && $singleFilter['value'] == 3) {
                    $query->where('bookings.status', 'canceled');
                } else if (array_key_exists('filterKey', $singleFilter) && $singleFilter['filterKey'] == "date_range") {
                    $query->whereBetween('bookings.booking_date', [$singleFilter['value'][0]['start'], $singleFilter['value'][0]['end']]);
                }
            }
        }
        if (empty($requestType)) {
            $count = $query->get()->count();
            $allData = $query->get();
            $data = $query->orderBy($request->columnKey, $request->columnSortedBy)->take($request->rowLimit)->skip($request->rowOffset)->get();

            $allSet = new AllSettingsFormat();

            $data->map(function ($item) use ($allSet) {
                $item->booking_time = $allSet->timeFormat(unserialize($item->booking_time));
            });
            return ['data' => $data, 'count' => $count];
        } else {
            return $query->orderBy($request->columnKey, $request->columnSortedBy)->get();
        }
    }
}
