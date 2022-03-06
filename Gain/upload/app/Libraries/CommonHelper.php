<?php


namespace App\Libraries;


use App\Models\Setting;

class CommonHelper
{
    public static function convertToMinutes($str)
    {
        $arr = explode(":", $str);
        $hours = intval($arr[0]);
        $mins = intval($arr[1]);
        return ($hours * 60) + $mins;
    }

    public static function getBusinessType()
    {
        $business_type = Setting::getSettingValue('business_type');
        return $business_type->setting_value;
    }
}