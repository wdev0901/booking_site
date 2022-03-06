<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\SmsTemplateRequest as Request;
use App\Services\SmsTemplateService;
use App\Http\Controllers\Controller;

class SmsTemplateController extends Controller
{
    /**
     * SmsTemplateController constructor.
     * @param SmsTemplateService $service
     */
    public function __construct(SmsTemplateService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $data = $this->service->getSmsTemplate(\request());
        $count = $this->service->smsTemplateCount();
        return ['datarows' => $data, 'count' => $count];
    }

    /**
     * @param $id
     * @return array
     */
    public function show($id)
    {
        return $this->service->getTemplateData($id);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        return $this->service->updateTemplate($request->all());
    }
}
