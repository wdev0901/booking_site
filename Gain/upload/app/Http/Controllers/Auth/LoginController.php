<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Dotenv\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Validation\ValidationException;
use Mail;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function showLoginForm()
    {
        $copyright_text = Setting::query()
            ->where('setting_name', 'copyright_text')
            ->first()->setting_value;

        return view('auth.login',
            [
                'email' => env('IS_DEV') ? 'admin@demo.com' : '',
                'password' => env('IS_DEV') ? '123456' : '',
                'copyright_text' => $copyright_text
            ]);
    }


//        $copyright_text = Setting::where('setting_name', 'copyright_text')->first()->setting_value;
//        return view('auth.login',
//            ['copyright_text' => $copyright_text]
//        );


    protected function validateLogin(Request $request)
    {
        /* Validate filed if the field is email,   need to validate for both email and phone number for same input field */

        if (strpos($request->email, '@') !== false) {
            $this->validate(
                $request,
                [
                    $this->username() => [
                        'required', 'string',
                        Rule::exists('users')->where(function ($query) {
                            $query->where('verified', true);
                        })
                    ], 'password' => 'required|string',
                ],
                [
                    $this->username() . '.exists' => Lang::get('lang.inactive_invalid_email')
                ]
            );
        }
    }

    public function userlogout()
    {
        Auth::guard('web')->logout();

        return redirect('/');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $userPassword = Hash::make($request->password);

        /*
         *  Remove "+", "white space", "-"  from input
         *    if the input consists all number then it is phone number
         *    else it is email
        */
        $check = str_replace("+","",$request->get('email'));
        $check = str_replace("-","",$check);
        $check = preg_replace('/\s+/', '', $check);


        if(is_numeric($check)){

            //phone
            /*Remove leading zoros*/
            $phoneNumber = ltrim($request->email, '0');
            $user = User::where('phone_object', 'LIKE', '%' . $phoneNumber . '%')->first();

        }else{
            //email
            $user = User::where('email', $request->email)->first();
        }


        // Check if user is 'client' and have permission to login
        if ($user && Hash::check($request->password, $user->password)) {

            if ($user->is_admin == 0) {

                if ($user->role_id == 0) {

                    $appPermission = Setting::select('setting_value')->where('setting_name', 'can_login')->first();

                    if (!$appPermission || $appPermission->setting_value != 1) {
                        throw ValidationException::withMessages([
                            $this->username() => Lang::get('lang.have_not_permission_to_login'),
                        ]);
                    }

                } else {

                    if ($user->disabled == 1) {
                        throw ValidationException::withMessages([
                            $this->username() => Lang::get('lang.have_not_permission_to_login'),
                        ]);
                    }
                }
            }
        }

        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    protected function credentials(Request $request)
    {

        if(is_numeric($request->get('email'))){
            //phone
            return [
                'phone_object'=>ltrim($request->email, '0'),
                'password'=>$request->get('password')
            ];
        }

        return $request->only($this->username(), 'password');
    }

}