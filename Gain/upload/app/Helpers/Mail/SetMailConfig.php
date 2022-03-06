<?php


namespace App\Helpers\Mail;


use App\Exceptions\GeneralException;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;

class SetMailConfig
{
    public function set()
    {
        $delivery_keys  = config('settings.delivery_keys');
        $settings = $this->getSettings($delivery_keys);

        throw_if(!$settings->filter(), (new GeneralException(trans('default.email_settings_are_not_added_yet'), 404)));

        foreach ($delivery_keys as $k => $v) {
            config()->set($k, $settings[$v]);
        }
    }

    public function getSettings($delivery_keys)
    {
        return Setting::query()
            ->whereIn('setting_name', array_values($delivery_keys))
            ->pluck('setting_value', 'setting_name');
    }
}