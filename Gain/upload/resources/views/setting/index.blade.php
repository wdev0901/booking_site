@extends('layouts.app')

@section('title', trans("lang.settings").' '.'-')

@section('content')
    @inject('perm', 'App\Http\Controllers\API\PermissionController')

    <setting-index
            roles={{$perm->roleManagePermission()}}
                    users={{$perm->userManagePermission()}}
                    app_settings={{$perm->appsManagePermission()}}
                    email_settings={{$perm->emailsManagePermission()}}
                    sms_settings={{$perm->smsManagePermission()}}
                    sms_templates={{$perm->smsManagePermission()}}
                    off_day={{$perm->offdaysManagePermission()}}
                    holiday={{$perm->holidaysManagePermission()}}
                    break_time={{$perm->breakTimeManagePermission()}}
                    email_templates={{$perm->etemplateManagePermission()}}
                    client_settings={{$perm->clientSettingsPermission()}}
                    payment_settings={{$perm->paymentSettingsPermission()}}
                    custom_input_settings={{$perm->customInputSettingsPermission()}}
                    service_policy={{$perm->servicePolicySettingsPermission()}}
                    social_link={{$perm->socialLinkSettingsPermission()}}
                    contact_information={{$perm->contactInfoSettingPermission()}}
                    updates={{$perm->updatePermission()}}
                    :all_time_zone={{json_encode($allTimezone)}}
    ></setting-index>

@endsection
