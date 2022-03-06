<?php

namespace App\Http\Controllers\API;

use App\Libraries\Permissions;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;

class EmailTemplateController extends Controller
{

    public function permissionCheck()
    {
        $controller = new Permissions;
        return $controller;
    }

    public function show($id)
    {
        $emailTemplate = EmailTemplate::find($id);
        if ($emailTemplate->custom_content) {
            $response = [
                'emailSubject' => $emailTemplate->template_subject,
                'content' => $emailTemplate->custom_content,
                'isCustom' => true,
            ];
        } else {
            $response = [
                'emailSubject' => $emailTemplate->template_subject,
                'content' => $emailTemplate->default_content,
                'isCustom' => false,
            ];
        }
        return $response;
    }

    public function update(Request $request, $emailTemplate)
    {
        $this->validate($request, [
            'subject' => 'required',
        ]);
        $subject = $request->input('subject');
        $custom_content = $request->input('custom_content');

        $eTemplate = EmailTemplate::findOrFail($emailTemplate);

        $eTemplate->template_subject = $subject;
        $eTemplate->custom_content = $custom_content;

        if ($eTemplate->update()) {
            $response = [
                'message' => Lang::get('lang.email_templates_successfully_update')
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'message' => Lang::get('lang.error_update')
            ];
            return response()->json($response, 404);
        }
    }

    public function templateList(Request $request)
    {
        $data = EmailTemplate::getEmailTemplate($request);
        $totalCount = EmailTemplate::countData();
        return ['datarows' => $data, 'count' => $totalCount];
    }

}
