<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;
use PDF;
use App\User;
use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Setting;
use App\Libraries\Email;
use App\Models\Services;
use App\Models\ContactInfo;
use App\Models\InvoiceItem;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Libraries\Permissions;
use App\Models\CustomDataField;
use App\Http\Controllers\Controller;
use App\Libraries\AllSettingsFormat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function bookingIndex()
    {
        return view('booking.index');
    }

    public function index($id)
    {
        return view('booking.bookingInfoForm', ['id' => $id]);
    }

    public function invoiceData($column, $id)
    {

        $invoiceData = Invoice::Join('users', 'invoices.user_id', '=', 'users.id')
            ->leftjoin('payments', 'invoices.booking_id', '=', 'payments.booking_id')
            ->select('invoices.*', 'users.first_name', 'users.last_name', 'users.email', 'users.phone', 'payments.paid_amount')
            ->where($column, $id)
            ->first();

        $invoiceItemData = InvoiceItem::where('invoice_id', $invoiceData->id)->get();
        $invoiceLogo = Setting::where('setting_name', 'invoice_logo')->first();
        $companyName = Setting::where('setting_name', 'company_name')->first();
        $companyInfo = Setting::where('setting_name', 'company_info')->first();
        $companyaAddress = ContactInfo::first();

        $allSet = new AllSettingsFormat;

        foreach ($invoiceItemData as $item) {
            $booking = array();
            $booking = unserialize($item->booking_time);
            $item->booking_time = $booking[0];
            $item->booking_time = $allSet->timeFormat($item->booking_time);
            $item->booking_date = $allSet->getDate($item->booking_date);
            $item->unit_price = $allSet->getCurrency($item->unit_price);
            $item->total = $allSet->getCurrency($item->total);
            return array(
                'name' => $invoiceData->first_name . " " . $invoiceData->last_name,
                'email' => $invoiceData->email,
                'phone' => $invoiceData->phone,
                'invoiceId' => $invoiceData->id,
                'due' => $allSet->getCurrency($invoiceData->total - $invoiceData->paid_amount),
                'total' => $allSet->getCurrency($invoiceData->total),
                'invoiceCreatedAt' => $allSet->getDate($invoiceData->created_at),
                'paid' => $allSet->getCurrency($invoiceData->paid_amount),
                'invoiceItemData' => $invoiceItemData,
                'invoiceLogo' => !empty($invoiceLogo) ? $invoiceLogo->setting_value : '',
                'companyName' => !empty($companyName) ? $companyName->setting_value : '',
                'companyInfo' => !empty($companyInfo) ? $companyInfo->setting_value : '',
                'companyAddress' => !empty($companyaAddress) ? $companyaAddress->address : '',
            );
        }
    }

    public function sendPdf($invoiceId, $mailText, $adminMailText, $email, $subject, $adminEmailSubject)
    {

        $data = $this->invoiceData('invoices.id', $invoiceId);
        $fileNameToStore = 'Booking-' . $invoiceId . '.pdf';
        $pdf = PDF::loadView('invoice.invoice', $data);
        $pdf->save('uploads/pdf/' . $fileNameToStore);

        $emailSend = new Email;
        $emailSend->sendEmail($mailText, $email, $subject, $fileNameToStore);

        //send email notification to admin
        $adminList = User::where('is_admin', 1)->get();


        foreach ($adminList as $admin){
            $admin_name = $admin['first_name']. " ".$admin['last_name'];
            $adminMailText = str_replace("{admin_name}", $admin_name, $adminMailText);
            $emailSend->sendEmail($adminMailText, $admin['email'], $adminEmailSubject, $fileNameToStore);
        }
        unlink(public_path('uploads/pdf/' . $fileNameToStore));
    }

    public function generateInvoice($id)
    {

        $data = $this->invoiceData('invoices.booking_id', $id);
        $pdf = PDF::loadView('invoice.invoice', $data);

        return $pdf->stream('invoice.pdf');
    }


    public function salonBooking(Request $request, $id)
    {
        return $request->all();
    }

    public function setBooking(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'seat' => 'required',
        ]);

        $allSet = new AllSettingsFormat;
        $isAdmin = new Permissions;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $email = $request->email;
        $phone = $request->phone_number;
        $token = str::random(25);
        $adult = $request->adult;
        $children = $request->children;
        $customer_name = $first_name ." ". $last_name ;

        $booking_time_new = [];
        foreach ($request->slot as $key => $item) {
            $item = strstr($item, '-', true);
            array_push($booking_time_new, $item);
        }
        $booking_time = serialize($allSet->setTimeFormat($booking_time_new));

        $quantity = $request->seat;
        $payment_id = $request->payment_id;
        $random_id = $request->random_id;
        $currency_code = $allSet->currencyCode();

        $user = User::where('email', '=', $email)->first();

        if ($user == null) {
            User::create(['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'phone' => $phone, 'token' => $token]);
        }

        //either phone or email
        /*if ($email){
            $user = User::where('phone_object', $request->phone_object)->first();

            if ($user == null) {
                $verification_code =  mt_rand(100000, 999999);
                User::create(['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'phone' => $phone, 'phone_object' => $verification_code, 'token' => $token]);
            }

        }else{
            $user = User::where('email', '=', $email)->first();

            if ($user == null) {
                User::create(['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'phone' => $phone, 'token' => $token]);
            }
        }*/

        $serviceDetails = Services::select('price', 'title', 'service_duration_type', 'business_type', 'percentage', 'auto_confirm')->where('id', $request->id)->first();

        $user_id = User::select('id')->where('email', $email)->first();

        $price = $serviceDetails->price;
        $booking_date = $request->booking_date;
        $comment = $request->comment;
        $title = $serviceDetails->title;
        $serviceType = $serviceDetails->service_duration_type;
        $auto_conf = $serviceDetails->auto_confirm;
        $method_id = $request->method_id;
        $paid_amount = 0;
        $payment = '';
        $timeZone = Setting::where('setting_name', 'time_zone')->first()->setting_value;
        $durationTime = Services::getService($request->id)->service_duration;
        $insertGoogleCalender = new GoogleCalendarController();

        if ($serviceDetails->service_duration_type == "daily") {
            // insert into google calendar
            $eventIdList = $insertGoogleCalender->dailysaveEvents($title, $booking_date, $comment);
            $eventIdList = serialize($eventIdList);
        } elseif ($serviceDetails->service_duration_type == "hourly") {
            //insert into google calendar

            foreach ($booking_time_new as $key => $val) {
                $tempStartTime = Carbon::parse($val);
                $tempEndTime = Carbon::parse($val)->addHours(intval(date("H", strtotime($durationTime))))->addMinutes(intval(date("i", strtotime($durationTime))))->addSeconds(00)->toTimeString();

                $startTime = $booking_date . 'T' . date("H:i:s", strtotime($tempStartTime));
                $endTime = $booking_date . 'T' . date("H:i:s", strtotime($tempEndTime));

                $eventIdList = $insertGoogleCalender->hourlysaveEvents($title, $startTime, $endTime, $timeZone, $comment);
                $eventIdList = serialize($eventIdList);
            }
        }

        $appName = Setting::where('setting_name', 'email_from_name')->first()->setting_value;

        if ($serviceDetails->service_duration_type == 'daily') {
            $paid_amount = $quantity * $price;
        } else {
            if ($serviceDetails->business_type == 'salon') {
                $child_price = $price - (($price * $serviceDetails->percentage) / 100);
                $total_child_price = $child_price * $children;
                $total_adult_price = $price * $adult;
                $paid_amount = ($total_child_price + $total_adult_price) * sizeof($request->slot);
            } else $paid_amount = $quantity * $price * sizeof($request->slot);
        }

        $adminContent = EmailTemplate::select('template_subject', 'default_content', 'custom_content')->where('template_type', 'admin_notification_for_booking_request')->first();
        $adminEmailSubject = $adminContent->template_subject;

        if ($isAdmin->isAdmin() || $auto_conf == 1) {

            $booking_id = Booking::create(['service_id' => $request->id, 'user_id' => $user_id->id, 'phone_number' => $phone, 'status' => 'confirmed', 'booking_date' => $booking_date, 'booking_time' => $booking_time, 'quantity' => $quantity, 'comment' => $comment, 'adult' => $adult, 'children' => $children, 'booking_bill' => $paid_amount, 'calendar_event_id' => $eventIdList]);

            // invoice related code start here
            $invoiceStore = Invoice::create(['user_id' => $user_id->id, 'booking_id' => $booking_id->id, 'total' => $paid_amount, 'due' => $paid_amount, 'created_by' => '', 'comment' => $comment]);
            $invoiceId = $invoiceStore->id;
            InvoiceItem::create(['invoice_id' => $invoiceId, 'service_title' => $title, 'booking_date' => $booking_date, 'booking_time' => $booking_time, 'unit_price' => $price, 'quantity' => $quantity, 'total' => $paid_amount]);

            $b_id = $booking_id->id;

            if ($request->paid_amount) Payment::create(['booking_id' => $b_id, 'currency_code' => $currency_code, 'paid_amount' => $request->paid_amount, 'method_id' => $request->method_id]);
            else Payment::create(['booking_id' => $b_id, 'currency_code' => $currency_code, 'paid_amount' => 0, 'method_id' => 0]);

            $content = EmailTemplate::select('template_subject', 'default_content', 'custom_content')->where('template_type', 'booking_confirmation')->first();

            $subject = $content->template_subject;

            if ($content->custom_content) {
                $text = $content->custom_content;
                $adminText = $adminContent->custom_content;
            } else {
                $text = $content->default_content;
                $adminText = $adminContent->default_content;
            }

            if ($request->paid_amount < $paid_amount) {
                $payment = Lang::get('lang.due');
            } else {
                $payment = Lang::get('lang.paid');
            }

            $b_time = array();
            $booking_time = unserialize($booking_time);

            foreach ($booking_time as $item) {

                $item = $allSet->timeFormat($item);
                array_push($b_time, $item);
            }

            $b_time = serialize($b_time);

            if ($serviceType === 'daily') {
                $mailText = str_replace('{booking_id}', $b_id, str_replace('{first_name}', $first_name, str_replace('{last_name}', $last_name, str_replace('{service_title}', $title, str_replace('{booking_quantity}', $quantity, str_replace('{booking_status}', 'Confirmed', str_replace('{paid_amount}', $allSet->getCurrency($paid_amount), str_replace('{app_name}', $appName, str_replace('{booking_date}', $allSet->getDate($booking_date), str_replace('{booking_slot}', '-', str_replace('{payment_status}', $payment, $text)))))))))));
                $adminMailText = str_replace('{booking_id}', $b_id,
                    str_replace('{customer_name}', $customer_name,
                        str_replace('{service_title}', $title,
                            str_replace('{booking_quantity}', $quantity,
                                str_replace('{booking_status}', 'Confirmed',
                                    str_replace('{paid_amount}', $allSet->getCurrency($paid_amount),
                                        str_replace('{app_name}', $appName,
                                            str_replace('{booking_date}', $allSet->getDate($booking_date),
                                                str_replace('{booking_slot}', '-',
                                                    str_replace('{payment_status}', $payment, $adminText))))))))));
            } else {
                $mailText = str_replace('{booking_id}', $b_id, str_replace('{first_name}', $first_name, str_replace('{last_name}', $last_name, str_replace('{service_title}', $title, str_replace('{booking_quantity}', $quantity, str_replace('{booking_status}', 'Confirmed', str_replace('{paid_amount}', $allSet->getCurrency($paid_amount), str_replace('{app_name}', $appName, str_replace('{booking_date}', $allSet->getDate($booking_date), str_replace('{booking_slot}', implode(', ', unserialize($b_time)), str_replace('{payment_status}', $payment, $text)))))))))));
                $adminMailText = str_replace('{booking_id}', $b_id,
                    str_replace('{customer_name}', $customer_name,
                        str_replace('{service_title}', $title,
                            str_replace('{booking_quantity}', $quantity,
                                str_replace('{booking_status}', 'Confirmed',
                                    str_replace('{paid_amount}', $allSet->getCurrency($paid_amount),
                                        str_replace('{app_name}', $appName,
                                            str_replace('{booking_date}', $allSet->getDate($booking_date),
                                                str_replace('{booking_slot}', implode(', ', unserialize($b_time)),
                                                    str_replace('{payment_status}', $payment, $adminText))))))))));
            }

            //pdf generate and email send code
            $this->sendPdf($invoiceId, $mailText,$adminMailText, $email, $subject, $adminEmailSubject);

            //save custom fields
            $customData = new CustomDataController();
            $customData->insertUpdateCustomFieldData('booking', $b_id, json_decode($request->customFields), false);

        } else {

            $transaction_id = $request->transaction_id;
            $booking_id = Booking::create(['service_id' => $request->id, 'user_id' => $user_id->id, 'phone_number' => $phone, 'booking_date' => $booking_date, 'booking_time' => $booking_time, 'quantity' => $quantity, 'comment' => $comment, 'adult' => $adult, 'children' => $children, 'booking_bill' => $paid_amount, 'calendar_event_id' => $eventIdList]);

            // invoice related code start here
            $invoiceStore = Invoice::create(['user_id' => $user_id->id, 'booking_id' => $booking_id->id, 'total' => $paid_amount, 'due' => $paid_amount, 'created_by' => '', 'comment' => $comment]);
            $invoiceId = $invoiceStore->id;
            InvoiceItem::create(['invoice_id' => $invoiceId, 'service_title' => $title, 'booking_date' => $booking_date, 'booking_time' => $booking_time, 'unit_price' => $price, 'quantity' => $quantity, 'total' => $paid_amount]);

            $b_id = $booking_id->id;

            if ($request->paid_amount) Payment::create(['booking_id' => $b_id, 'currency_code' => $currency_code, 'paid_amount' => $request->paid_amount, 'method_id' => $request->method_id, 'transaction_id' => $transaction_id]);
            else Payment::create(['booking_id' => $b_id, 'currency_code' => $currency_code, 'paid_amount' => 0, 'method_id' => 0, 'transaction_id' => $transaction_id]);

            $content = EmailTemplate::select('template_subject', 'default_content', 'custom_content')->where('template_type', 'booking_received')->first();

            $subject = $content->template_subject;

            if ($content->custom_content) {
                $text = $content->custom_content;
                $adminText = $adminContent->custom_content;
            }
            else {
                $text = $content->default_content;
                $adminText = $adminContent->default_content;
            }

            if ($request->paid_amount < $paid_amount) $payment = Lang::get('lang.due');
            else $payment = Lang::get('lang.paid');

            $b_time = array();
            $booking_time = unserialize($booking_time);

            foreach ($booking_time as $item) {

                $item = $allSet->timeFormat($item);
                array_push($b_time, $item);
            }

            $b_time = serialize($b_time);

            if ($serviceType === 'daily') {
                $mailText = str_replace('{booking_id}', $b_id, str_replace('{first_name}', $first_name, str_replace('{last_name}', $last_name, str_replace('{service_title}', $title, str_replace('{booking_quantity}', $quantity, str_replace('{booking_status}', 'Pending', str_replace('{paid_amount}', $allSet->getCurrency($paid_amount), str_replace('{app_name}', $appName, str_replace('{booking_date}', $allSet->getDate($booking_date), str_replace('{booking_slot}', '-', str_replace('{payment_status}', $payment, $text)))))))))));
                $adminMailText = str_replace('{booking_id}', $b_id,
                    str_replace('{customer_name}', $customer_name,
                        str_replace('{service_title}', $title,
                            str_replace('{booking_quantity}', $quantity,
                                str_replace('{booking_status}', 'Pending',
                                    str_replace('{paid_amount}', $allSet->getCurrency($paid_amount),
                                        str_replace('{app_name}', $appName,
                                            str_replace('{booking_date}', $allSet->getDate($booking_date),
                                                str_replace('{booking_slot}', '-',
                                                    str_replace('{payment_status}', $payment, $adminText))))))))));
            } else {
                $mailText = str_replace('{booking_id}', $b_id, str_replace('{first_name}', $first_name, str_replace('{last_name}', $last_name, str_replace('{service_title}', $title, str_replace('{booking_quantity}', $quantity, str_replace('{booking_status}', 'Pending', str_replace('{paid_amount}', $allSet->getCurrency($paid_amount), str_replace('{app_name}', $appName, str_replace('{booking_date}', $allSet->getDate($booking_date), str_replace('{booking_slot}', implode(', ', unserialize($b_time)), str_replace('{payment_status}', $payment, $text)))))))))));
                $adminMailText = str_replace('{booking_id}', $b_id,
                    str_replace('{customer_name}', $customer_name,
                        str_replace('{service_title}', $title,
                            str_replace('{booking_quantity}', $quantity,
                                str_replace('{booking_status}', 'Pending',
                                    str_replace('{paid_amount}', $allSet->getCurrency($paid_amount),
                                        str_replace('{app_name}', $appName,
                                            str_replace('{booking_date}', $allSet->getDate($booking_date),
                                                str_replace('{booking_slot}', implode(', ', unserialize($b_time)),
                                                    str_replace('{payment_status}', $payment, $adminText))))))))));
            }

            //send email notification to customer
            $this->sendPdf($invoiceId, $mailText,$adminMailText, $email, $subject, $adminEmailSubject);

            $customData = new CustomDataController();
            $customData->insertUpdateCustomFieldData('booking', $b_id, json_decode($request->customFields), false);
        }

        $notifcations = new Notification();
        $notifcations->event = 'submitted_a_new_booking';
        $notifcations->booking_id = $b_id;
        $notifcations->booking_by = User::select('id')->where('email', $email)->first()->id;
        $notifcations->activity_id = $notifcations->booking_by;

        if (Auth::user()) {
            $notifcations->notify_to = User::select('id')->where('is_admin', '1')->where('id', '!=', Auth::user()->id)->get(); //send notifications to users
        } else {
            $notifcations->notify_to = User::select('id')->where('is_admin', '1')->get(); //send notifications to users
        }

        $notify = '';

        foreach ($notifcations->notify_to as $notify_to) {

            $notify = $notify . ',' . $notify_to->id;
        }

        $notifcations->notify_to = $notify;
        $notifcations->save();

        $response = [
            'message' => Lang::get('lang.booking_saved_successfully')
        ];
        return response()->json($response, 200);
    }

    public function action(Request $request, $id)
    {
        $allSet = new AllSettingsFormat;
        $status = Booking::find($id);
        $quantity = $status->quantity;
        $booking_date = $status->booking_date;
        $booking_time = array();
        $b_time = unserialize($status->booking_time);

        foreach ($b_time as $item) {

            $item = $allSet->timeFormat($item);
            array_push($booking_time, $item);
        }

        $serviceDetails = Services::select('price', 'title', 'service_duration_type')->where('id', $status->service_id)->first();

        $price = $serviceDetails->price;
        $title = $serviceDetails->title;
        $serviceType = $serviceDetails->service_duration_type;

        $user = User::select('id', 'first_name', 'last_name', 'email')->where('id', $status->user_id)->first();

        $email = $user->email;
        $first_name = $user->first_name;
        $last_name = $user->last_name;

        $appName = Setting::select('setting_value')->where('setting_name', 'email_from_name')->first()->setting_value;
        $auth_user = Auth::user();

        if ($request->status == 'confirmed') {
            $notifcations = new Notification();
            $notifcations->event = 'confirmed_the_booking';
            $notifcations->booking_id = $id;

            $notifcations->booking_by = User::select('id')->where('email', $email)->first()->id;
            $notifcations->activity_id = $auth_user->id;

            $notifcations->notify_to = User::select('id')->where('is_admin', '1')->where('id', '!=', Auth::id())->get(); //send notifications to users
            $payment_stat = Payment::select('paid_amount')->where('booking_id', $status->id)->first()->paid_amount;

            $notify = '';

            foreach ($notifcations->notify_to as $notify_to) {

                $notify = $notify . ',' . $notify_to->id;
            }

            $notifcations->notify_to = $notify;
            $notifcations->save();

            if ($payment_stat < $status->booking_bill) {
                $payment = Lang::get('lang.due');
            } else {
                $payment = Lang::get('lang.paid');
            }

            $content = EmailTemplate::select('template_subject', 'default_content', 'custom_content')->where('template_type', 'booking_confirmation')->first();
            $subject = $content->template_subject;

            if ($content->custom_content) {
                $text = $content->custom_content;
            } else {
                $text = $content->default_content;
            }

            $paid_amount = 0;

            if (sizeof($booking_time) == 0) {
                $paid_amount = $quantity * $price;
            } else {
                $paid_amount = $quantity * $price * sizeof($booking_time);
            }

            if ($serviceType === 'daily') {
                $mailText = str_replace('{booking_id}', $status->id, str_replace('{first_name}', $first_name, str_replace('{last_name}', $last_name, str_replace('{service_title}', $title, str_replace('{booking_quantity}', $quantity, str_replace('{booking_status}', 'Confirmed', str_replace('{paid_amount}', $allSet->getCurrency($paid_amount), str_replace('{app_name}', $appName, str_replace('{booking_date}', $allSet->getDate($booking_date), str_replace('{booking_slot}', '-', str_replace('{payment_status}', $payment, $text)))))))))));
            } else {
                $mailText = str_replace('{booking_id}', $status->id, str_replace('{first_name}', $first_name, str_replace('{last_name}', $last_name, str_replace('{service_title}', $title, str_replace('{booking_quantity}', $quantity, str_replace('{booking_status}', 'Confirmed', str_replace('{paid_amount}', $allSet->getCurrency($paid_amount), str_replace('{app_name}', $appName, str_replace('{booking_date}', $allSet->getDate($booking_date), str_replace('{booking_slot}', implode(', ', $booking_time), str_replace('{payment_status}', $payment, $text)))))))))));
            }

            $emailSend = new Email;
            $emailSend->sendEmail($mailText, $email, $subject);
        } else {
            $notifcations = new Notification();
            $notifcations->event = 'canceled_the_booking';
            $notifcations->booking_id = $id;

            $notifcations->booking_by = User::select('id')->where('email', $email)->first()->id;
            $notifcations->activity_id = $auth_user->id;

            $notifcations->notify_to = User::select('id')->where('is_admin', '1')->where('id', '!=', Auth::id())->get(); //send notifications to users

            $notify = '';

            foreach ($notifcations->notify_to as $notify_to) {

                $notify = $notify . ',' . $notify_to->id;
            }

            $notifcations->notify_to = $notify;
            $notifcations->save();

            $content = EmailTemplate::select('template_subject', 'default_content', 'custom_content')->where('template_type', 'booking_rejected')->first();
            $subject = $content->template_subject;

            if ($content->custom_content) {
                $text = $content->custom_content;
            } else {
                $text = $content->default_content;
            }

            if ($serviceType === 'daily') {
                $mailText = str_replace('{booking_id}', $status->id, str_replace('{first_name}', $first_name, str_replace('{last_name}', $last_name, str_replace('{service_title}', $title, str_replace('{booking_status}', 'Canceled', str_replace('{app_name}', $appName, str_replace('{booking_quantity}', $quantity, str_replace('{app_name}', $appName, str_replace('{booking_date}', $allSet->getDate($booking_date), str_replace('{booking_slot}', '-', $text))))))))));
            } else {
                $mailText = str_replace('{booking_id}', $status->id, str_replace('{first_name}', $first_name, str_replace('{last_name}', $last_name, str_replace('{service_title}', $title, str_replace('{booking_status}', 'Canceled', str_replace('{app_name}', $appName, str_replace('{booking_quantity}', $quantity, str_replace('{app_name}', $appName, str_replace('{booking_date}', $allSet->getDate($booking_date), str_replace('{booking_slot}', implode(', ', $booking_time), $text))))))))));
            }

            $emailSend = new Email;
            $emailSend->sendEmail($mailText, $email, $subject);
        }

        $status->update($request->all());
        $returnData = [
            'message' => ''
        ];
        if ($request->status == 'confirmed') {
            $returnData['message'] = Lang::get('lang.booking_is_confirmed');
        } elseif ($request->status == 'canceled') {
            $returnData['message'] = Lang::get('lang.booking_is_canceled');
        }
        return response()->json($returnData, 200);
    }

    public function getBookingList(Request $request)
    {
        $business_type = Setting::getSettingValue('business_type');
        $getBookingList = Booking::getBookingList($request, $business_type->setting_value);


        $allGetBookingList = $getBookingList['data'];

        foreach ($allGetBookingList as $value) {
            if ($value->booking_bill - $value->paid_amount == 0) {
                $value->payment_status = "paid";
            } else {
                $value->payment_status = "due";
            }
        }

        $customData = new CustomDataController();
        $addCustomData = $customData->showCustomDataInDataTable('booking', $allGetBookingList);

        $allGetBookingList = $addCustomData['data'];
        $columnName = $addCustomData['columnName'];
        $customDateId = $addCustomData['customDateId'];

        return ['datarows' => $allGetBookingList, 'count' => $getBookingList['count'], 'columnName' => $columnName, 'customDateId' => $customDateId];
    }

    public function bookingDetails($id, $return_data = false)
    {
        $settingAll = new AllSettingsFormat;

        $booking = Booking::Join('services', 'bookings.service_id', '=', 'services.id')
            ->join('payments', 'bookings.id', '=', 'payments.booking_id')
            ->leftJoin('payment_methods', 'payments.method_id', '=', 'payment_methods.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->select('bookings.*', 'users.id as user_id', 'users.avatar', 'users.first_name', 'users.last_name', 'users.email', 'users.phone', 'services.title', 'services.price', 'services.service_duration_type', 'payments.paid_amount', 'payment_methods.title as method_title', 'payment_methods.id as payment_method_id', 'payments.id as payment_id')
            ->find($id);

        $booking['payment_stat'] = $booking->booking_bill - $booking->paid_amount;
        $booking->paid_amount = $settingAll->getCurrency((string)($settingAll->thousandSep(count(unserialize($booking->booking_time)) * $booking->quantity * $booking->price)));
        $booking->booking_time = $settingAll->timeFormat(unserialize($booking->booking_time));
        $booking->booking_bill = $settingAll->getCurrency($booking->booking_bill);
        $booking->booking_date = $settingAll->getDate($booking->booking_date);

        $customDataInfo = CustomDataField::join('custom_fields', 'custom_fields.id', 'custom_data_fields.custom_fields_id')
            ->where('custom_data_fields.table_name', 'booking')
            ->where('custom_data_fields.data_id', $id)
            ->select('custom_fields.label', 'custom_data_fields.value')
            ->get();

        if ($return_data == false) {
            return view('booking.view', ['booking' => $booking, 'customFieldData' => $customDataInfo]);
        } else {
            return $booking;
        }
    }

    public function bookingPaymentDetails($id)
    {
        $settingAll = new AllSettingsFormat;

        $paymentDetails = Payment::leftJoin('payment_methods', 'payments.method_id', '=', 'payment_methods.id')
            ->leftJoin('users', 'payments.created_by', '=', 'users.id')
            ->select('payments.*', 'payment_methods.title as payment_method', 'users.first_name', 'users.last_name')
            ->find($id);

        $paymentDetails->created = $paymentDetails->created_at->format('m/d/Y');
        $paymentDetails->created = $settingAll->getDate($paymentDetails->created);

        $paymentDetails->paid_amount = $settingAll->getCurrency($paymentDetails->paid_amount);

        return $paymentDetails;
    }

    public function delete($id)
    {
        Booking::where('id', $id)->delete();
        $customData = new CustomDataController();
        $customData->deleteCustomFieldsRecords('bookings', $id);
        $response = [
            'message' => Lang::get('lang.booking_deleted_successfully')
        ];
        return response()->json($response, 200);
    }

    public function show($id){
        $bookingData = Booking::find($id);
        $service = $bookingData ? Services::find($bookingData->service_id) : null ;
        $user = $bookingData ? User::find($bookingData->user_id) : null ;

        $bookingData->booking_time = $this->getTiming($bookingData, $service);
        return [
            'bookingData' => $bookingData,
            'service' => $service,
            'user' => $user
        ];
    }

    public function getTiming($bookingData, $serviceData)
    {
        $allSet = new AllSettingsFormat;

        $stackSlot = unserialize($bookingData->booking_time);
        $finalSlot = [];

        foreach ($stackSlot as $key => $value) {
            $endTime = date("H:i:s", strtotime($value) - strtotime("00:00:00") + strtotime($serviceData->service_duration));
            $finalSlot[] = $allSet->timeFormat($value, true) . " - " . $allSet->timeFormat($endTime, true);
        }
        return $finalSlot;
    }

    public function update($id, Request $request){
        $allSet = new AllSettingsFormat;
        $appName = Setting::where('setting_name', 'email_from_name')->first()->setting_value;
        $booking_time_new = [];
        $data = Booking::find($id);
        $service = $data ? Services::find($data->service_id) : null ;
        $user = $data ? User::find($data->user_id) : null ;
        $payment = $data ? Payment::where('booking_id', $id)->first() : null ;
        $invoice = $data ? Invoice::where('booking_id', $id)->first() : null ;

        $output['booking_date'] = $request->booking_date;

        foreach ($request->booking_time as $key => $item) {
            $item = strstr($item, '-', true);
            array_push($booking_time_new, $item);
        }
        $output['booking_time'] = serialize($allSet->setTimeFormat($booking_time_new));
        $data->update($output);

        
        if ($invoice->due > 0) $payment_status = Lang::get('lang.due');
        else $payment_status = Lang::get('lang.paid');
    
        $customer_name = $user->first_name ." ". $user->last_name;

        $mailText = $this->makeEmailTemplate($data->id, $user->first_name, $user->last_name, $service->title, 
        $data->quantity, $data->status, $payment->paid_amount, $request->booking_date, $booking_time_new, $payment_status);

        $adminMailText = $this->makeAdminEmailTemplate($data->id, $customer_name,$service->title, $data->quantity, 
        $data->status, $payment->paid_amount, $request->booking_date, $booking_time_new, $payment_status);

        // dd($mailText['subject']);
        $this->sendPdf($invoice->id, $mailText['text'], $adminMailText['text'], $user->email, $mailText['subject'], $adminMailText['subject']);

        $response = [
            'message' => Lang::get('lang.booking_updated_successfully')
        ];
        return response()->json($response, 201);
    }

    public function makeEmailTemplate($id, $first_name, $last_name, $title, $quantity, $status, $paid_amount, $booking_date, $booking_time, $payment_status){
        $allSet = new AllSettingsFormat;
        $appName = Setting::where('setting_name', 'email_from_name')->first()->setting_value;
        $content = EmailTemplate::select('template_subject', 'default_content', 'custom_content')->where('template_type', 'booking_received')->first();
        if ($content->custom_content) $text = $content->custom_content;
        else $text = $content->default_content;

        $mailText = str_replace('{booking_id}', $id, 
                    str_replace('{first_name}', $first_name, 
                    str_replace('{last_name}', $last_name,
                    str_replace('{service_title}', $title,
                    str_replace('{booking_quantity}', $quantity, 
                    str_replace('{booking_status}', $status, 
                    str_replace('{paid_amount}', $allSet->getCurrency($paid_amount), 
                    str_replace('{app_name}', $appName, 
                    str_replace('{booking_date}', $allSet->getDate($booking_date), 
                    str_replace('{booking_slot}', implode(', ', $booking_time), 
                    str_replace('{payment_status}', $payment_status, $text)))))))))));

        return [
            'text' => $mailText,
            'subject' => $content->template_subject
        ];
    }
    public function makeAdminEmailTemplate($id, $customer_name, $title, $quantity, $status, $paid_amount, $booking_date, $booking_time, $payment_status){
        $allSet = new AllSettingsFormat;
        $appName = Setting::where('setting_name', 'email_from_name')->first()->setting_value;

        $adminContent = EmailTemplate::select('template_subject', 'default_content', 'custom_content')->where('template_type', 'admin_notification_for_booking_request')->first();

        if ($adminContent->custom_content) $text = $adminContent->custom_content;
        else $text = $adminContent->default_content;

        $adminMailText = str_replace('{booking_id}', $id,
                        str_replace('{customer_name}', $customer_name,
                        str_replace('{service_title}', $title,
                        str_replace('{booking_quantity}', $quantity,
                        str_replace('{booking_status}', $status,
                        str_replace('{paid_amount}', $allSet->getCurrency($paid_amount),
                        str_replace('{app_name}', $appName,
                        str_replace('{booking_date}', $allSet->getDate($booking_date),
                        str_replace('{booking_slot}', implode(', ', $booking_time),
                        str_replace('{payment_status}', $payment_status, $text))))))))));
        return [
            'text' => $adminMailText,
            'subject' => $adminContent->template_subject
        ];
    }
}
