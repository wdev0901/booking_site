<?php

namespace App\Libraries;


use App\Exceptions\GeneralException;
use App\Helpers\Mail\SetMailConfig;
use Illuminate\Support\Facades\Mail;

class Email
{
    public function sendEmail($mailText, $email, $subject = null, $fileNameToStore = null)
    {
        (new SetMailConfig)->set();

        try {

            Mail::send([], [], function ($message) use ($email, $subject, $mailText, $fileNameToStore) {

                $mailer = $message->to($email)
                    ->subject($subject)
                    ->setBody($mailText, 'text/html');

                if ($fileNameToStore) {
                    $pdf =  'uploads/pdf/' . $fileNameToStore;
                    $mailer->Attach(\Swift_Attachment::fromPath($pdf));
                }

                return $mailer;
            });

            return true;

        } catch (\Exception $exception) {
            throw new GeneralException(trans('default.something_wrong_with_your_email_settings'), 401);
        }

    }

}