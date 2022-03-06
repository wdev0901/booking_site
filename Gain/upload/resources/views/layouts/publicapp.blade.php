@include('layouts.include.head')
<body id="home">
@inject('appConfig', 'App\Http\Controllers\Controller')
@inject('basicData', 'App\Http\Controllers\API\SettingController')
<div id="app">
    <main id="app">
        @yield('content')
    </main>
</div>

<?php
    $gain = config('gain');
    $appVersion = $gain['app_version'];
?>
<script>
    window.appConfig = {
        appUrl: "{{ $appConfig->appUrl }}",
        publicPath: " {{ $appConfig->publicPath }}",
        dateFormat: "{{ $basicData->dateFormat() }} ",
        currencySymbol: "{{ $appConfig->currency_symbol }}",
        currencyCode: "{{ $appConfig->currency_code }}",
        currencyFormat: "{{ $appConfig->currency_format }}",
        thousandSeparator: "{{ $appConfig->thousand_separator }}",
        decimalSeparator: "{{ $appConfig->decimal_separator }}",
        numberOfDecimal: "{{ $appConfig->number_of_decimal }}>",
        appLogo: "{{ $appConfig->app_logo }}",
        appName: "{{  $appConfig->app_name }} ",
        appVersion: "{{ $appVersion}}",
    }
</script>
@include('layouts.include.footer')