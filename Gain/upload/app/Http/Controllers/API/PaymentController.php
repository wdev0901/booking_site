<?php

namespace App\Http\Controllers\API;

use App\Libraries\AllSettingsFormat;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\Libraries\PayPalClient;
use Illuminate\Support\Str;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;

class PaymentController extends Controller
{
    public function paymentIndex()
    {
        $data = PaymentMethod::all();

        return $data;
    }

    public function getAllMethods()
    {
        $payment_methods = PaymentMethod::query()->where('available_to_client', 1)->where('enable', 1)->get();

        return $payment_methods->map(function ($method) {
            if ($method->option) {
                $keys = unserialize($method->option);
                $method->setAttribute('option', $keys[1]);
            }
            return $method;
        });
    }

    public function getPaymentList(Request $request)
    {
        $payments = PaymentMethod::getPaymentType($request);


        foreach ($payments['data'] as $payment) {

            if ($payment->available_to_client == 1) $payment->available_to_client = Lang::get('lang.yes');
            else $payment->available_to_client = Lang::get('lang.no');

            if ($payment->enable == 1) $payment->enable = Lang::get('lang.yes');
            else $payment->enable = Lang::get('lang.no');

        }

        return ['datarows' => $payments['data'], 'count' => $payments['count']];
    }

    public function show($id)
    {
        $data = PaymentMethod::find($id);
        $data->option = unserialize($data->option);

        return $data;
    }

    public function store(Request $request)
    {
        PaymentMethod::create(['title' => $request->title, 'type' => 'custom', 'available_to_client' => $request->available_to_client, 'enable' => $request->enable]);
        $response = [
            'message' => Lang::get('lang.payment_method_saved_successfully')
        ];
        return response()->json($response, 201);
    }


    public function updatePayment(Request $request, $id)
    {
        try {
            $payment = PaymentMethod::find($id);

            if ($request->publickey) {
                $request['option'] = serialize([$request->secret_key, $request->publickey]);

            } elseif ($request->client_id) {
                $request['option'] = serialize([$request->secret_key, $request->client_id, $request->mode_paypal]);

            }

            $payment->update($request->all());

            $response = [
                'message' => Lang::get('lang.payment_method_updated_successfully')
            ];
            return response()->json($response, 201);

        } catch (\Exception $e) {

            return $e;
        }

    }

    public function paymentFunction(Request $request)
    {
        $keys = PaymentMethod::select('option')->where('type', 'stripe')->first();
        $keys = unserialize($keys->option);
        $currency_code = Setting::select('setting_value')->where('setting_name', 'currency_code')->first()->setting_value;

        \Stripe\Stripe::setApiKey($keys[0]);
        // Token is created using Checkout or Elements!
        // Get the payment token ID submitted by the form:
        $token = $request->token;
        // Charge the user's card:

        if ($charge = \Stripe\Charge::create(array(
            "amount" => $request->bill * 100,
            "currency" => $currency_code,
            "description" => Lang::get('lang.example_charge'),
            "source" => $token,
        ))) {

            $rand_id = str::random(20);
            $responce = ['payment' => 'done'];

            return response()->json($responce, 200);

        } else {
            $responce = ['isvalid' => false];

            return response()->json($responce, 401);
        }


    }

    // Due pay by Admin
    public function manualPayment(Request $request)
    {
        Payment::where('booking_id', $request->booking_id)
            ->update(['paid_amount' => Payment::select('paid_amount')
                    ->where('booking_id', $request->booking_id)
                    ->first()->paid_amount + $request->paid_amount, 'method_id' => $request->method_id, 'created_by' => Auth::user()->id]);
        $response = [
            'message' => Lang::get('lang.payment_done_successfully')
        ];
        return response()->json($response, 200);

    }

    public function duePayment($id)
    {
        $allSettings = new AllSettingsFormat;
        $paymentDetails = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->select('payments.*', 'bookings.status', 'bookings.booking_bill')
            ->where('booking_id', $id)->first();

        $paymentDetails['payment_stat'] = $allSettings->getCurrency($paymentDetails->booking_bill - $paymentDetails->paid_amount);
        $paymentDetails->booking_bill = $allSettings->getCurrency($paymentDetails->booking_bill);
        $paymentMethods = PaymentMethod::select('id', 'title')->where('type', 'custom')->where('enable', 1)->get();
        $data = ['paymentDetails' => $paymentDetails, 'paymentMethods' => $paymentMethods];

        return $data;
    }

    public function destroy($id)
    {
        $paymentmethod = PaymentMethod::find($id);

        if ($paymentmethod) {
            $paymentmethod->delete();
            $response = [
                'message' => Lang::get('lang.payment_method_deleted_successfully')
            ];
            return response()->json($response, 201);
        }
    }

    public function paypalPayment(Request $request)
    {
        $orderId = $request->orderID;

        try {
            $client = PayPalClient::client();
            $response = $client->execute(new OrdersGetRequest($orderId));
            $details = $response->result;
            if ($orderId == $details->id) {

                return "success";
            }

        } catch (\Exception $error) {

            return $error;
        }
    }
}
