<?php

namespace App\Http\Controllers\API;

use App\Libraries\AllSettingsFormat;
use App\Models\Booking;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;

class ClientDashboardController extends Controller
{
    public function dashboardClientBookingList(Request $request)
    {
        $settingAll = new AllSettingsFormat;

        $userId = auth()->id();

        $clientBookingList = User::getClientBookingList($request, $userId);

        $allClientBookingList = $clientBookingList['data'];

        foreach ($allClientBookingList as $value) {
            if ($value->booking_time) {
                $value->booking_time = $settingAll->timeFormat(unserialize($value->booking_time));
            }

            if ($value->booking_date) {
                $value->booking_date = $settingAll->getDate($value->booking_date);
            }

            if ($value->booking_bill - $value->paid_amount == 0) {
                $value->payment_status = "paid";
            } else {
                $value->payment_status = "due";
            }
        }
        return ['datarows' => $allClientBookingList, 'count' => $clientBookingList['count']];
    }

    public function getData()
    {
        $user = Auth::user()->id;
        $totalBooking = 0;
        $pendingBooking = 0;
        $confirmBooking = 0;
        $cancelBooking = 0;

        $bookingStatus = Booking::where('user_id', $user)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        foreach ($bookingStatus as $booking) {

            if ($booking->status == 'pending') {
                $pendingBooking = $booking->count;

            } else if ($booking->status == 'confirmed') {
                $confirmBooking = $booking->count;

            } else {
                $cancelBooking = $booking->count;
            }

            $totalBooking += $booking->count;
        }

        return [
            'totalBooking' => $totalBooking,
            'pendingBooking' => $pendingBooking,
            'confirmBooking' => $confirmBooking,
            'cancelBooking' => $cancelBooking,
        ];
    }

}
