<?php

namespace App\Http\Controllers\API;

use App\Models\ServicePolicy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;

class ServicePolicyController extends Controller
{
    public function index(Request $request)
    {
        $services = ServicePolicy::first();
        return $services;
    }

    public function edit(ServicePolicy $servicePolicy)
    {
        return $servicePolicy;
    }

    public function update(Request $request, ServicePolicy $servicePolicy)
    {
        $servicePolicyUpdated = $servicePolicy->update($request->all());
        if ($servicePolicyUpdated) {
            $response = [
                'message' => Lang::get('lang.service_policy_successfully_update')
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'message' => Lang::get('lang.getting_problems')
            ];
            return response()->json($response, 400);
        }
    }

    public function getservicePolicy()
    {
        return ServicePolicy::first();
    }
}
