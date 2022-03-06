<?php

namespace App\Http\Controllers\API;

use App\Models\Setting;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class GoogleCalendarController extends Controller
{
    public function saveGoogleCalendar(Request $request)
    {
        $clientId = $request->clientId;
        $clientSecret = $request->clientSecret;
        $userId = Auth::user()->id;
        $error = true;
        $redirectUrl = $this->getRedirectUrl();

        $status = $this->getAuthCodeUrl($clientId, $clientSecret, $redirectUrl);
        $calenderInfo = serialize(["clientId" => $clientId, 'clientSecret' => $clientSecret, "error" => $error]);
        $checkGoogleClient = Setting::where('setting_name', 'google-calendar')->where('user_id', $userId)->exists();

        if ($checkGoogleClient) {

            Setting::where('setting_name', 'google-calendar')->where('user_id', $userId)->update(["setting_value" => $calenderInfo]);

        } else {
            Setting::insert(["setting_name" => 'google-calendar', "setting_value" => $calenderInfo, 'user_id' => $userId]);
        }

        $response = ['msg' => Lang::get('lang.google_calendar_save_successfully'), "status" => $status];

        return response()->json($response, 200);

    }

    public function getAuthCodeUrl($clientId, $clientSecret, $redirectUrl)
    {
        try {
            $client = new Google_Client();
            $client->setApplicationName('googlecalendar');
            $client->setScopes(Google_Service_Calendar::CALENDAR);
            $client->setClientId($clientId);
            $client->setClientSecret($clientSecret);
            $client->setRedirectUri($redirectUrl);
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');
            $authUrl = $client->createAuthUrl();

            return $authUrl;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function getAuthCode($authCode)
    {
        $userId = Auth::user()->id;
        $client = Setting::where('setting_name', 'google-calendar')->where('user_id', $userId)->first();
        $credentials = unserialize($client->setting_value);

        try {
            $url = 'https://accounts.google.com/o/oauth2/token';

            $post_data = array(
                'code' => $authCode,
                'client_id' => $credentials['clientId'],
                'client_secret' => $credentials['clientSecret'],
                'redirect_uri' => $this->getRedirectUrl(),
                'grant_type' => 'authorization_code',
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            $token = json_decode($result);
            $credentials['refreshToken'] = $token->refresh_token;
            $credentials['error'] = false;

        } catch (\Exception $e) {
            $credentials['error'] = true;
        }

        $newCredentials = serialize($credentials);
        Setting::where('setting_name', 'google-calendar')->where('user_id', $userId)->update(["setting_value" => $newCredentials]);
    }

    public function getGoogleCalendar()
    {
        $userId = Auth::user()->id;
        $id = '';
        $secret = '';
        $error = false;

        $client = Setting::where('setting_name', 'google-calendar')->where('user_id', $userId)->first();

        if ($client) {
            $credentials = unserialize($client->setting_value);
            $id = $credentials['clientId'];
            $secret = $credentials['clientSecret'];
            $error = $credentials['error'];
        }

        $response = ['id' => $id, 'secret' => $secret, 'error' => $error];

        return response()->json($response, 200);
    }


    public function dailysaveEvents($title, $booking_date, $comment)
    {
        $googleClients = Setting::where('setting_name', 'google-calendar')->get();
        $eventIdList = [];

        foreach ($googleClients as $client) {

            try {
                $credentials = unserialize($client->setting_value);
                $id = $credentials['clientId'];
                $secret = $credentials['clientSecret'];
                $token = $credentials['refreshToken'];

                $authClient = $this->getClient($id, $secret, $token);

                if ($authClient) {
                    $service = new \Google_Service_Calendar($authClient);
                    $event = new \Google_Service_Calendar_Event(array(
                        'summary' => $title,
                        'description' => $comment,
                        'start' => array(
                            'date' => $booking_date,
                        ),
                        'end' => array(
                            'date' => $booking_date,
                        ),
                        'reminders' => array(
                            'useDefault' => FALSE,
                        ),
                    ));

                    $calendarId = 'primary';
                    $event = $service->events->insert($calendarId, $event);
                    array_push($eventIdList, $event->id);

                }
            } catch (\Exception $e) {

            }
        }

        return $eventIdList;

    }

    public function hourlysaveEvents($title, $startTime, $endTime, $timeZone, $comment)
    {
        $googleClients = Setting::where('setting_name', 'google-calendar')->get();
        $eventIdList = [];

        foreach ($googleClients as $client) {

            try {
                $credentials = unserialize($client->setting_value);
                $id = $credentials['clientId'];
                $secret = $credentials['clientSecret'];
                $token = $credentials['refreshToken'];

                $authClient = $this->getClient($id, $secret, $token);

                if ($authClient) {
                    $service = new \Google_Service_Calendar($authClient);
                    $event = new \Google_Service_Calendar_Event(array(
                        'summary' => $title,
                        'description' => $comment,
                        'start' => array(
                            'dateTime' => $startTime,
                            'timeZone' => $timeZone,
                        ),
                        'end' => array(
                            'dateTime' => $endTime,
                            'timeZone' => $timeZone,
                        ),
                        'reminders' => array(
                            'useDefault' => FALSE,
                        ),
                    ));

                    $calendarId = 'primary';
                    $event = $service->events->insert($calendarId, $event);
                    array_push($eventIdList, $event->id);

                }
            } catch (\Exception $e) {

            }
        }

        return $eventIdList;
    }

    public function getClient($clientId, $secret, $refreshToken)
    {
        try {
            $url = "https://www.googleapis.com/oauth2/v4/token";
            $data = [
                'client_id' => $clientId,
                'client_secret' => $secret,
                'refresh_token' => $refreshToken,
                'grant_type' => "refresh_token",
            ];

            $client = new \Google_Client();
            $client->setApplicationName('googlecalendar');
            $client->setScopes(\Google_Service_Calendar::CALENDAR);
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
            $client->setAccessToken((array)json_decode($response));

            return $client;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function getRedirectUrl()
    {
        $publicPath = \Request::root();
        return $publicPath . '/myprofile';
    }
}
