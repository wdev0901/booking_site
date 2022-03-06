@include('setup.layouts.head')
<body id="home">
@inject('appConfig', 'App\Http\Controllers\Controller')
@php
    $appVersion = config('gain.app_version');
@endphp
<div id="app">
    <main id="app">
        @yield('content')
    </main>
</div>
<script>
    window.appConfig = {
        appUrl: "<?= $appConfig->appUrl ?>",
        app_name: "<?= $appConfig->app_name ?>",
        publicPath: "<?= $appConfig->publicPath ?>",

        dateTimeFormat: "",
        dateFormat: "",
        knowDefaultRowSettings: "",
        currentUserId: "",

        currencySymbol: "{{ $appConfig->currency_symbol }}",
        currencyFormat: "{{ $appConfig->currency_format }}",
        thousandSeparator: "{{ $appConfig->thousand_separator }}",
        decimalSeparator: "{{ $appConfig->decimal_separator }}",
        numberOfDecimal: "{{ $appConfig->number_of_decimal }}>",
        appLogo: "{{ $appConfig->app_logo }}",
        appName: "{{  $appConfig->app_name }} ",
        currencyCode: "{{ $appConfig->currency_code }}",
        timeFormat: "{{ $appConfig->time_format }}",
        businessType: "{{ $appConfig->business_type }}",
        appVersion: "{{ $appVersion}}",
    }
</script>
@include('setup.layouts.footer')
