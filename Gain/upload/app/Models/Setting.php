<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Setting extends Model
{
    protected $fillable = [
        'setting_name', 'setting_value', 'setting_type', 'user_id', 'created_by',
    ];

    public static function saveCacheData()
    {
        $allData1 = Cache::remember('settings', 24 * 60, function () {
            $allData = Setting::all();

            foreach ($allData as $data) {
                if ($data->setting_name == 'offday_setting') {
                    $data->setting_value = explode(',', $data->setting_value);
                }
            }

            return $allData->pluck('setting_value', 'setting_name');
        });

        return $allData1;
    }

    public static function updateSettingData($data)
    {
        foreach ($data as $key => $value) {
            $updateData = Setting::where('setting_name', $key)->update(['setting_value' => $value]);
        }
        return $updateData;
    }

    public static function getSettingValue($name)
    {
        return Setting::select('setting_name', 'setting_value')->where('setting_name', $name)->first();
    }
}
