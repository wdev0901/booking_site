<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use Illuminate\Support\Facades\Lang;


class ContactInformationController extends Controller
{
    public function getContactInfo()
    {
        return ContactInfo::first();
    }

    public function contactInfoUpdate(Request $request, ContactInfo $contactInfo)
    {
        $contactInfo->title = $request->title;
        $contactInfo->sub_title = $request->sub_title;
        $contactInfo->address = $request->address;
        $contactInfo->email = $request->contact_email;
        $contactInfo->phone = $request->phone;
        $contactInfo->save();

        if ($contactInfo) {
            $response = [
                'message' => Lang::get('lang.contact_information_successfully_updated')
            ];
            return response()->json($response, 201);

        } else {

            $response = [
                'message' => Lang::get('lang.something_went_wrong')
            ];

            return response()->json($response, 401);
        }
    }
}
