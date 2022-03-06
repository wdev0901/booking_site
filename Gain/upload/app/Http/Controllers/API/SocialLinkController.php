<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Permissions;
use Illuminate\Support\Facades\Lang;
use App\Models\SocialLink;
use DB;

class SocialLinkController extends Controller
{

    public function permissionCheck()
    {
        $controller = new Permissions;
        return $controller;
    }

    public function index()
    {
        $social = SocialLink::get();
        return $social->pluck('social_link_name', 'social_link_value');

    }

    public function update(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            SocialLink::query()->where('social_link_value', $key)->update(['social_link_name' => $value]);
        }
        $response = [
            'message' => Lang::get('lang.social_link_update')
        ];
        return response()->json($response, 201);
    }


    public function getSocialData()
    {
        $front_social = SocialLink::get();
        return $front_social->pluck('social_link_name', 'social_link_value');
    }
}
