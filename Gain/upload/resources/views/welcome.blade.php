@inject('appConfig', 'App\Http\Controllers\Controller')

@extends('layouts.publicapp')

@section('title')

@section('content')
    <?php $sessionStatus = ''; ?>
    @if(session('status'))
        <?php $sessionStatus = session('status'); ?>
    @endif

    @if($appConfig->business_type == 'general')
        @guest
            <calendar-seven-days sessionstatus="{{ $sessionStatus }}"
                                 landing_page_message="{{ $landingPageMessage }}"
                                 landing_page_header="{{ $landingPageHeader }}"
                                 :checksignup="{{ $signupCheck }}"
                                 :service="{{$service}}"
                                 :user="null">
            </calendar-seven-days>
        @else
            <calendar-seven-days sessionstatus="{{ $sessionStatus }}"
                                 landing_page_message="{{ $landingPageMessage }}"
                                 landing_page_header="{{ $landingPageHeader }}"
                                 :checksignup="{{ $signupCheck }}"
                                 :service="{{$service}}"
                                 :user="{{ $user }}">
            </calendar-seven-days>
        @endguest
    @elseif($appConfig->business_type == 'salon')
        @guest

            <salon_component landing_page_header="{{ $landingPageHeader }}"
                             landing_page_message="{{ $landingPageMessage }}"
                             copyright_text="{{ $copyright_text }}"
                             :checksignup="{{ $signupCheck }}"
                             :service="{{$service}}"
                             :user="null">
            </salon_component>

        @else
            <salon_component landing_page_header="{{ $landingPageHeader }}"
                             landing_page_message="{{ $landingPageMessage }}"
                             copyright_text="{{ $copyright_text }}"
                             :checksignup="{{ $signupCheck }}"
                             :service="{{ $service }}"
                             :user="{{ $user }}">
            </salon_component>
        @endguest
    @endif
@endsection
