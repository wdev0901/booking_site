<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    public static function getContactInfo($request)
    {

        if (empty($requestType)) {
            $count = self::get()->count();
            $allData = self::get();
            return ['datarows' => $allData, 'count' => $count];
        } else {
            return self::orderBy($request->columnKey, $request->columnSortedBy)->get();
        }
    }
}
