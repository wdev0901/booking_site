<?php

namespace App\Models;

class PaymentMethod extends BaseModel
{
    protected $fillable = ['title', 'type', 'available_to_client', 'enable', 'option'];

    public static function getPaymentType($request)
    {
        $count = PaymentMethod::count();
        $data = PaymentMethod::get();

        return ['data' => $data, 'count' => $count];
    }
}
