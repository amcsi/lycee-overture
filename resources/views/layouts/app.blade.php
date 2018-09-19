<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Lycee Overture TCG Translations</title>

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

</head>
<body>
@yield('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</body>
</html>
