@php
$openGraphImageUrl = 'https://media.archonia.com/images/samples/74/87/307487_s0.jpg';
@endphp
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Lycee Overture TCG Translations</title>

    <meta name="description" content="This is a website in progress with the goal of translating the this new Lycee Overture Trading Card Game and provide a useful database for being able to search for cards.">
    <meta name="keywords" content="lycee, lycee overture, tcg, trading card game, english, japanese, translation, database">

    <meta property="og:image" content="{{ $openGraphImageUrl }}" />
    <meta property="og:image:secure_url" content="{{ $openGraphImageUrl }}" />

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: "Helvetica Neue", Helvetica, "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", "微软雅黑", Arial, sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
    </style>

    @if (env('GA_TRACKING_ID'))
        {{-- Global site tag (gtag.js) - Google Analytics --}}
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GA_TRACKING_ID') }}"></script>
        <script>
          window.dataLayer = window.dataLayer || [];

          function gtag() {dataLayer.push(arguments);}

          gtag('js', new Date());

          gtag('config', '{{ env('GA_TRACKING_ID') }}');
        </script>
    @endif

    <script>
      var locale = @json(\App::getLocale());
    </script>

</head>
<body>
@yield('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</body>
</html>
