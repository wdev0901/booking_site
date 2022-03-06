<?php

namespace App\Http\Controllers\API;

use App\Libraries\AllSettingsFormat;
use App\Libraries\Permissions;
use App\Models\Booking;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;

class ClientController extends Controller
{
    public function permissionCheck()
    {
        $controller = new Permissions;
        return $controller;
    }

    public function index()
    {
        return view('clients.index');
    }

    public function allClients(Request $request)
    {
        return User::getAllClients($request);
    }

    public function userDetails($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function edit($clientid)
    {
        $user = User::find($clientid);
        return $user;
    }

    public function updateClient(Request $request, $id)
    {
        if ($this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
        ])) {
            $user = User::find($id);
            $user->update($request->all());
            $response = [
                'message' => Lang::get('lang.client_updated_successfully')
            ];
            return response()->json($response, 201);

        } else {
            $response = [
                'message' => Lang::get('lang.getting_problems')
            ];
            return response()->json($response, 200);
        }
    }

    public function show($id)
    {
        $settingAll = new AllSettingsFormat;

        $user = User::find($id);
        $user->date_of_birth = $settingAll->getDate($user->date_of_birth);

        $totalBooking = Booking::where('user_id', $id)->count();

        return view('clients.view', ['user' => $user, 'totalbooking' => $totalBooking]);
    }

    public function clientBookingList(Request $request, $userid)
    {

        $settingAll = new AllSettingsFormat;

        $clientBookingList = User::getClientBookingList($request, $userid);

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

    public function delete($id)
    {
        $user = User::find($id);
        $isExistsInBooking = User::join('bookings', 'bookings.user_id', '=', 'users.id')->where('user_id', $id)->first();
        if ($isExistsInBooking == null) {
            User::where('id', $id)->delete();
            $customData = new CustomDataController();
            $customData->deleteCustomFieldsRecords('users', $id);
            $response = [
                'message' => Lang::get('lang.client_deleted_successfully')
            ];
            return response()->json($response, 201);
        } else {
            $response = [
                'message' => Lang::get('lang.client_in_use')
            ];
            return response()->json($response, 200);
        }
    }
}
