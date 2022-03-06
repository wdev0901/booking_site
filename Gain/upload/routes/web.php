<?php

use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\BreakTimeController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\ClientDashboardController;
use App\Http\Controllers\API\ContactInformationController;
use App\Http\Controllers\API\CustomFieldController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\EmailTemplateController;
use App\Http\Controllers\API\GoogleCalendarController;
use App\Http\Controllers\API\HolidayController;
use App\Http\Controllers\API\InviteController;
use App\Http\Controllers\API\LocalizationController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\RoleAssignController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\ServicePolicyController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\SmsTemplateController;
use App\Http\Controllers\API\SocialLinkController;
use App\Http\Controllers\API\UpdateController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstallDemoDataController;
use App\Http\Controllers\Setup\EnvironmentController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/**
 * Hi there I just formatted all the routes which is to support laravel 8 *
 */
Route::get('/', [HomeController::class, 'homePage'])
    ->name('welcome');
Route::get('/demo', [HomeController::class, 'chooseDemoPage']);
Route::post('/update-business-type', [HomeController::class, 'updateBusinessType']);
Route::get('services/{alias}', [HomeController::class, 'homePage']);
Route::post('contact-us', [HomeController::class, 'contactUs']);
Route::get('get-contact-info', [ContactInformationController::class, 'getContactInfo']);
Route::post('contact-info-update/{contactInfo}', [ContactInformationController::class, 'contactInfoUpdate']);

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::post('/recover', [AuthController::class, 'recover']);

Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequest']);
Route::post('/recoverpass', [AuthController::class, 'recover']);
Route::get('password/reset/{token}', [ResetPasswordController::class, 'resetForm']);

Route::post('/password/reset', [AuthController::class, 'reset']);

Route::get('/verify/{token}', [RegisterController::class, 'verifyUser']);
Route::get('accept/{token}', [InviteController::class, 'accept']);
Route::get('register/{token}', [RegisterController::class, 'regForm']);
Route::post('register/{token}', [RegisterController::class, 'invitedRegistration']);
Route::get('register', [RegisterController::class, 'signup']);
Route::post('registerclient', [RegisterController::class, 'register']);
Route::post('/setcookie', [HomeController::class, 'setCookie']);
Route::get('/client-custom-field', [CustomFieldController::class, 'getClientBookingField']);
Route::post('sms-confirmation/{token}', [RegisterController::class, 'smsConfirmationRegistration']);
Route::post('resend-sms-confirmation', [RegisterController::class, 'resendVerificationCode']);
Route::get('/get-sms-setting-status', [SettingController::class, 'getSmsSettingStatus']);

// Auth middleware group Route
Route::group(['middleware' => 'auth'], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/dashboarddata', [DashboardController::class, 'getData']);

    // Profile Route
    Route::get('myprofile', [ProfileController::class, 'myProfile']);
    Route::get('myprofile/calender', [ProfileController::class, 'myProfileCalender']);
    Route::get('user-profile', [ProfileController::class, 'index']);
    Route::post('profile/{id}', [ProfileController::class, 'update']);
    Route::post('/updatePassword/{id}', [ProfileController::class, 'updatepassword']);
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::post('save-google-calendar', [GoogleCalendarController::class, 'saveGoogleCalendar']);
    Route::get('get-calender-data', [GoogleCalendarController::class, 'getGoogleCalendar']);
    Route::get('get-auth-code', [GoogleCalendarController::class, 'getAuthCode']);


    // Custom Field routes
    Route::post('service-custom-field', [CustomFieldController::class, 'serviceFieldData']);
    Route::post('booking-custom-field', [CustomFieldController::class, 'bookingFieldData']);
    Route::post('save-custom-field', [CustomFieldController::class, 'save']);
    Route::post('save-custom-field/{id}', [CustomFieldController::class, 'saveFields']);
    Route::post('/get-service-field', [CustomFieldController::class, 'getServiceData']);
    Route::post('/get-booking-field', [CustomFieldController::class, 'getBookingData']);
    Route::get('get-custom-field/{id}', [CustomFieldController::class, 'getCustomData']);
    Route::post('/get-service-custom-field', [CustomFieldController::class, 'getServiceField']);

    // Setting Service Delete
    Route::get('/customfiledservice-edit/{id}', [CustomFieldController::class, 'serviceFiledEdit']);
    Route::post('/customfiled-update/{id}', [CustomFieldController::class, 'update']);
    Route::post('/customfiled-service-delete/{id}', [CustomFieldController::class, 'deleteSettingservice']);
    Route::get('/customfiledbooking-edit/{id}', [CustomFieldController::class, 'bookingFiledEdit']);
    Route::post('/customfiledbooking-delete/{id}', [CustomFieldController::class, 'deleteSettingbooking']);


    // Email template Route
    Route::post('templatelist',  [EmailTemplateController::class, 'templateList']);

    Route::get('/gettemplatecontent/{id}', [EmailTemplateController::class, 'show'])
        ->middleware('permissions:can_edit_email_template');
    Route::post('/setcustomcontent/{id}', [EmailTemplateController::class, 'update'])
        ->middleware('permissions:can_edit_email_template');

    // Email Setting Route
    Route::post('/emailsetting', [SettingController::class, 'emailSettingSave'])
        ->middleware('permissions:can_edit_email_setting');
    Route::get('/emailsettingdata', [SettingController::class, 'emailSettingData'])
        ->middleware('permissions:can_edit_email_setting');
    Route::post('/testmail', [SettingController::class, 'testMail']);

    // Service Route
    Route::post('/serviceid/{id}', [ServiceController::class, 'update'])
        ->middleware('permissions:can_add_service');
    Route::post('/serviceSetting/{id}', [ServiceController::class, 'serviceSetting'])
        ->middleware('permissions:can_add_service');
    Route::post('/servicedeactive/{id}', [ServiceController::class, 'deactivate'])
        ->middleware('permissions:can_add_service');
    Route::post('/servicedefault/{id}', [ServiceController::class, 'setdefault'])
        ->middleware('permissions:can_add_service');
    Route::post('servicelist', [ServiceController::class, 'getData'])
        ->middleware('permissions:can_add_service');
    Route::post('serviceshow', [ServiceController::class, 'getSortedData'])
        ->middleware('permissions:can_add_service');
    Route::post('/deleteservice/{id}', [ServiceController::class, 'delete'])
        ->middleware('permissions:can_add_service');
    Route::get('servicenew/create', [ServiceController::class, 'create'])
        ->middleware('permissions:can_add_service');
    Route::post('servicenew/create/store', [ServiceController::class, 'store'])
        ->middleware('permissions:can_add_service');
    Route::get('service-userlist', [ServiceController::class, 'serviceUserlist'])
        ->middleware('permissions:can_add_service');


    // Service policy Route
    Route::get('servicepolicy', [ServicePolicyController::class, 'index']);
    Route::post('servicepolicy-store', [ServicePolicyController::class, 'store'])
        ->middleware('permissions:can_add_service_policy');
    Route::get('servicepolicy-edit/{servicePolicy}', [ServicePolicyController::class, 'edit'])
        ->middleware('permissions:can_edit_service_policy');
    Route::post('servicepolicy-update/{servicePolicy}', [ServicePolicyController::class, 'update'])
        ->middleware('permissions:can_update_service_policy');
    Route::post('servicepolicy-delete/{servicePolicy}', [ServicePolicyController::class, 'destroy'])
        ->middleware('permissions:can_delete_service_policy');

    // Social Link Route

    Route::get('social-link', [SocialLinkController::class, 'index'])
        ->middleware('permissions:can_manage_social_link');
    Route::post('social-link-update', [SocialLinkController::class, 'update'])
        ->middleware('permissions:can_manage_social_link');

    // Off Day Setting Route
    Route::post('/offdaysetting', [SettingController::class, 'offdays'])
        ->middleware('permissions:can_edit_off_day_setting');
    Route::get('/offdaysdata', [SettingController::class, 'offdaysData'])
        ->middleware('permissions:can_edit_off_day_setting');

    // Holiday Route
    Route::get('/holiday', [HolidayController::class, 'index'])
        ->middleware('permissions:can_edit_holi_day_setting');
    Route::post('/holiday-store', [HolidayController::class, 'store'])
        ->middleware('permissions:can_edit_holi_day_setting');
    Route::get('/holidays/{id}', [HolidayController::class, 'show'])
        ->middleware('permissions:can_edit_holi_day_setting');
    Route::post('/holiday-update/{id}', [HolidayController::class, 'updateHolidays'])
        ->middleware('permissions:can_edit_holi_day_setting');
    Route::post('/holiday-delete/{id}', [HolidayController::class, 'destroy'])
        ->middleware('permissions:can_edit_holi_day_setting');
    Route::post('getholidays', [HolidayController::class, 'sortedList'])
        ->middleware('permissions:can_edit_holi_day_setting');

    // View Route
    Route::get('/settings', [SettingController::class, 'index']);
    Route::get('/bookings', [BookingController::class, 'bookingIndex'])
        ->middleware('permissions:can_manage_booking');
    Route::post('/deletebooking/{id}', [BookingController::class, 'delete'])
        ->middleware('permissions:can_manage_booking');
    Route::get('/services', [ServiceController::class, 'services'])
        ->middleware('permissions:can_add_service');



    //client bookings
    Route::get('/bookingsclient', [BookingController::class, 'bookingIndex']);
    Route::post('/bookinglistclient', [ClientDashboardController::class, 'myprofile']);

    // App Setting Route
    Route::post('/basic-setting', [SettingController::class, 'basicSettingSave'])
        ->middleware('permissions:can_manage_application_setting');
    Route::get('/basicsettingdata', [SettingController::class, 'basicSettingData']);
    Route::get('/timezone', [SettingController::class, 'basicSettingData']);
    Route::get('/knowDefaultRowSettings', [SettingController::class, 'knowDefaultRowSettings']);

    Route::get('/dateformat', [SettingController::class, 'dateFormat']);
    // Invite Route
    Route::post('/invite', [InviteController::class, 'process'])
        ->middleware('permissions:invite');
    Route::get('/allroleid', [InviteController::class, 'getRoleId']);

    // Booking Route
    Route::get('/allbooking', [BookingController::class, 'allBookins']);
    Route::get('/inovoice-pdf/{id}', [BookingController::class, 'generateInvoice']);
    Route::post('/bookingshow', [BookingController::class, 'getBookingList']);
    Route::post('/actionbooking/{id}', [BookingController::class, 'action'])
        ->middleware('permissions:can_manage_booking');
    Route::get('/booking-payment/{id}', [BookingController::class, 'bookingPaymentDetails'])
        ->middleware('permissions:can_manage_booking');

    // Role Route
    Route::post('/roletitle', [RoleController::class, 'allData']);
    Route::get('/roletitleuser', [RoleController::class, 'allDataUser']);
    Route::post('/addrole', [RoleController::class, 'store'])
        ->middleware('permissions:roles');
    Route::post('/addrole/{id}', [RoleController::class, 'update'])
        ->middleware('permissions:roles');
    Route::get('/rolepermissions/{id}', [RoleController::class, 'getRolePermissions']);
    Route::get('/rolepermissions/', [RoleController::class, 'getRoleWithout']);
    Route::post('/deleterole/{id}', [RoleController::class, 'delete'])
        ->middleware('permissions:roles');

    // Notification Route
    Route::get('notify', [NotificationController::class, 'index']);
    Route::post('/upnotify/{id}', [NotificationController::class, 'update']);
    Route::post('countup/{id}', [NotificationController::class, 'countUp']);
    Route::get('notification', [NotificationController::class, 'allNotification']);

    //all users
    Route::post('/roleassign/{id}', [RoleAssignController::class, 'update'])
        ->middleware('permissions:roles');
    Route::post('userlist', [UserController::class, 'getUserList'])
        ->middleware('permissions:roles');
    Route::get('/user/{id}', [UserController::class, 'userDetails']);
    Route::get('/user-role/{id}', [UserController::class, 'getUserWithRole'])
        ->name('users.role');
    Route::post('/disableEnableUser/{id}', [UserController::class, 'disableEnableUser'])
        ->middleware('permissions:roles');
    Route::post('/user-delete/{id}', [UserController::class, 'delete'])
        ->middleware('permissions:roles');
    Route::post('/user-bookings/{id}', [UserController::class, 'userBookingList'])
        ->middleware('permissions:can_manage_user_setting');


    // Client setting
    Route::get('clientsetting', [SettingController::class, 'clientSetting']);
    Route::post('clientsetting', [SettingController::class, 'updateClientSetting'])
        ->middleware('permissions:can_manage_client_setting');
    Route::get('/clients', [ClientController::class, 'index'])
        ->middleware('permissions:can_manage_client_setting');
    Route::post('clientlist', [ClientController::class, 'allClients'])
        ->middleware('permissions:can_manage_client_setting');
    Route::post('/client-update/{id}', [ClientController::class, 'updateClient'])
        ->middleware('permissions:can_manage_client_setting');
    Route::get('/client-edit/{id}', [ClientController::class, 'edit'])
        ->middleware('permissions:can_manage_client_setting');
    Route::post('/deleteclient/{id}', [ClientController::class, 'delete'])
        ->middleware('permissions:can_manage_client_setting');
    Route::get('/client/{id}', [ClientController::class, 'show'])
        ->middleware('permissions:can_manage_client_setting');
    Route::post('/client-bookings/{id}', [ClientController::class, 'clientBookingList'])
        ->middleware('permissions:can_manage_client_setting');
//    Route::post('/dashboard-client-bookings', [ClientDashboardController::class, 'dashboardClientBookingList']);
    Route::any('/dashboard-client-bookings', [ClientDashboardController::class, 'dashboardClientBookingList']);

    Route::get('/clients/{id}', [ClientController::class, 'userDetails'])
        ->middleware('permissions:can_manage_client_setting');
    Route::get('/booking-details/{id}/{flag?}', [BookingController::class, 'bookingDetails'])
        ->middleware('permissions:can_manage_booking');
    Route::get('/clientdashboarddata', [ClientDashboardController::class, 'getData']);
    Route::post('/bookinglist', [ClientDashboardController::class, 'getBookingData'])
        ->middleware('permissions:can_manage_booking');
    Route::get('/invoice-details/{id}', [BookingController::class, 'invoiceDetails']);

    // Report route
    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware('permissions:can_see_reports');
    Route::get('/booking-report/{id}/{date}', [ReportController::class, 'getReport'])
        ->middleware('permissions:can_see_reports');
    Route::get('/getlangdir', [LocalizationController::class, 'getLangDir']);

    // Payments Route
    Route::get('/payments', [PaymentController::class, 'paymentIndex']);
    Route::post('/payments', [PaymentController::class, 'getPaymentList']);
    Route::post('/payment/store', [PaymentController::class, 'store'])
        ->middleware('permissions:can_manage_payment_methods');
    Route::get('/payments/{id}', [PaymentController::class, 'show'])
        ->middleware('permissions:can_manage_payment_methods');
    Route::post('/payments/{id}', [PaymentController::class, 'updatePayment'])
        ->middleware('permissions:can_manage_payment_methods');
    Route::get('/payment/payment-details/{id}', [PaymentController::class, 'duePayment']);
    Route::post('/payment/payment-details', [PaymentController::class, 'manualPayment'])
        ->middleware('permissions:can_manage_booking');
    Route::post('/payment-delete/{id}', [PaymentController::class, 'destroy'])
        ->middleware('permissions:can_manage_payment_methods');
    Route::post('/manualpayment/{id}', [PaymentController::class, 'manualPayment'])
        ->middleware('permissions:can_manage_booking');

    // Sms Setting Route

    Route::get('/get-sms-data', [SettingController::class, 'getSmsData']);

    Route::post('/sms-setting-update', [SettingController::class, 'smsSettingUpdate'])
        ->middleware('permissions:can_edit_sms_settings');
    Route::post('/sms-template-list', [SmsTemplateController::class, 'index'])
        ->name('templates');
    Route::get('/template/{id}', [SmsTemplateController::class, 'show'])
        ->name('templates-data');
    Route::post('/template/{id}/update', [SmsTemplateController::class, 'update'])
        ->name('templates-update');


    // Updates Route

    Route::get('/gain-update', [UpdateController::class, 'applicationVersion']);

    Route::get('/update-version-list', [UpdateController::class, 'versionUpdateList']);

    Route::post('/install-new-version/{version}', [UpdateController::class, 'updateAction']);

    Route::get('/update-list', [UpdateController::class, 'updateAppUrl']);

    Route::get('/curl_get_contents', [UpdateController::class, 'curl_get_contents']);

    Route::get('/nexInstallVersion', [UpdateController::class, 'nexInstallVersion']);

    // break time settings

    Route::post('/break-times', [BreakTimeController::class, 'index']);

    Route::post('/break-time/store', [BreakTimeController::class, 'store']);

    Route::post('/break-time/{id}', [BreakTimeController::class, 'update']);

    Route::get('/break-time/{id}', [BreakTimeController::class, 'show']);

    Route::get('/break-times', [BreakTimeController::class, 'getAllData']);

    Route::post('/break-time/{id}/delete', [BreakTimeController::class, 'destroy']);

});


Route::get('/clear-cache', function () {
     Artisan::call('cache:clear');
//    Cache::flush();
    return response()->json(['message' => \Illuminate\Support\Facades\Lang::get('lang.the_language_cache_has_been_removed')], 201);
});

Route::post('/paypal-transaction-complete', [PaymentController::class, 'paypalPayment']);

//service slot timings

Route::get('/servicetiming/{id}/{date}', [ServiceController::class, 'getTiming']);

Route::get('/servicetiming/{id}/', [ServiceController::class, 'getTimingZero']);

Route::get('serviceshowforbooking', [ServiceController::class, 'getServiceAndOffDays']);

Route::get('/servicelist', [ServiceController::class, 'activeData']);

Route::get('/serviceid/{id}', [ServiceController::class, 'show']);

Route::get('/service-by-id/{id}', [ServiceController::class, 'getServiceById']);

Route::get('/bookservice/{id}', [BookingController::class, 'index']);

Route::get('/getcurrency', [SettingController::class, 'getCurrency']);

Route::post('/bookservice', [BookingController::class, 'setBooking']);

Route::get('/paymentstripe', [PaymentController::class, 'paymentForm']);

Route::post('/paymentstripe', [PaymentController::class, 'paymentForm']);

Route::get('/getpmethods', [PaymentController::class, 'getAllMethods']);

Route::get('/single-book-service/{id}', [BookingController::class, 'show']);

Route::post('/update-booking/{id}', [BookingController::class, 'update']);

// salon service list

Route::get('/getAllServiceFormData', [ServiceController::class, 'getServiceListData']);

Route::post('/salon-service-book/{id}', [BookingController::class, 'salonBooking']);

Route::get('/getAllsocialData', [SocialLinkController::class, 'getSocialData']);

Route::get('getfrontservicepolicy', [ServicePolicyController::class, 'getservicePolicy']);

Route::get('/get-services/{id}', [ServiceController::class, 'getServiceList']);

Route::any('install-demo-data', [InstallDemoDataController::class, 'run'])
    ->name('install-demo-data');

// Localization
Route::get('/js/lang.js', function () {
    $strings = Cache::rememberForever('lang.js', function () {
        $lang = config('app.locale');
        $files = glob(resource_path('lang/' . $lang . '/*.php'));
        $strings = [];
        foreach ($files as $file) {
            $name = basename($file, '.php');
            if ($name !== "custom" && $name !== "default") {
                $new_keys = require $file;
                $strings = array_merge($strings, $new_keys);
            }
        }
        return $strings;
    });
    header('Content-Type: text/javascript');
    echo('window.i18n = ' . json_encode(array("lang" => $strings)) . ';');
    exit();
})->name('assets.lang');

Route::group(['prefix' => 'app'], function () {
    Route::get('environment', [EnvironmentController::class, 'index'])
        ->name('app.environment');

    Route::post('environment/database', [EnvironmentController::class, 'saveEnvironment'])
        ->name('app.environment.database');

    Route::get('environment/admin', [EnvironmentController::class, 'admin'])
        ->name('app.environment.admin');

    Route::post('environment/install', [EnvironmentController::class, 'store'])
        ->name('app.installer');
});