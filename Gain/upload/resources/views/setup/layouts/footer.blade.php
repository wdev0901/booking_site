@inject('app', 'App\Http\Controllers\API\SettingController')
<!-- Scripts -->
<?php
$publicPath = $app->getAppPublicPath();
$appDetails = config('gain');
$scriptSources = [
    [
        'comment' => '<!--Import lang.js-->',
        'src' => asset('/js/lang.js'),
    ],
    [
        'comment' => '<!--Import app.js-->',
        'src' => asset($publicPath . '/js/app.js'),
    ],
    [
        'comment' => '<!--Import locales-all.js-->',
        'src' => asset($publicPath . '/js/locales-all.js'),
    ],
    [
        'comment' => '<!--Import accounting.js-->',
        'src' => asset($publicPath . '/js/accounting.js'),
    ],
    [
        'comment' => '<!--Import summernote-lite.js-->',
        'src' => asset($publicPath . '/summernote-0.8.9/summernote-lite.js'),
    ],

    [
        'comment' => '<!--Import jqueryeasingminjs-->',
        'src' => asset($publicPath . '/js/jquery.easing.min.js'),
    ],

    [
        'comment' => '<!--Import menu-->',
        'src' => asset($publicPath . '/js/creative.js'),
    ],
];

foreach ($scriptSources as $scriptSource) {
    if ($scriptSource['comment']) {
        echo $scriptSource['comment'] . "\n";
    }

    echo "<script src='" . $scriptSource['src'] . "?app_version=" . $appDetails['app_version'] . "'></script> \n";

}
?>

</body>
</html>
