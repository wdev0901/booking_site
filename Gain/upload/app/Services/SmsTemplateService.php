<?php


namespace App\Services;


use App\Models\SmsTemplate;
use Illuminate\Support\Facades\Lang;


class SmsTemplateService extends BaseService
{
    /**
     * SmsTemplateService constructor.
     * @param SmsTemplate $smsTemplate
     */
    public function __construct(SmsTemplate $smsTemplate)
    {
        $this->model = $smsTemplate;
    }

    /**
     * @param $id
     * @return array
     */
    public function getTemplateData($id)
    {
        $templateData = $this->model->find($id);

        if ($templateData->custom_content) {
            $response = [
                'id' => $templateData->id,
                'template_type'=> $templateData->template_type,
                'template_subject' => $templateData->template_subject,
                'content' => $templateData->custom_content,
                'isCustom' => true,
            ];
        } else {
            $response = [
                'id' => $templateData->id,
                'template_type'=> $templateData->template_type,
                'template_subject' => $templateData->template_subject,
                'content' => $templateData->default_content,
                'isCustom' => false,
            ];
        }
        return $response;
    }

    public function getSmsTemplate($request)
    {
        return $this->model->orderby($request->columnKey, $request->columnSortedBy)->get();
    }

    public function smsTemplateCount()
    {
        return $this->model->count();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function updateTemplate($data)
    {
        $updateData = [
            "id" => $data['id'],
            "template_subject" => $data['template_subject'],
            "custom_content" => $data['custom_content']
        ];
        $this->model->where('id', '=', $data['id'])->update($updateData);
        $response = [
            'message' => Lang::get('lang.sms_templates_successfully_update')
        ];
        return response()->json($response, 200);
    }
}