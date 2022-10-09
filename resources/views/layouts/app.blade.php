@php
$title = 'Lycee Overture TCG Translations';
$description = 'This is a website in progress with the goal of translating the new Lycee Overture Trading Card Game and provide a useful database for being able to search for cards.';
$cardIds = request()->get('cardId') ?
    array_map(
        function (string $cardId): string {
            return sprintf('LO-%04d', preg_replace('/\D/', '', $cardId));
        },
        explode(',', request()->str('cardId'))
    ) :
    [];
$ogWidth = 249;
$ogHeight = 423;
$ogImageUrl = 'https://res.cloudinary.com/drkxqkguu/image/upload/q_auto,f_auto/v1619434147/og_image.jpg';
if (!empty($cardIds)) {
    $ogWidth = 346;
    $ogHeight = 484;
    $ogImageUrl = "https://res.cloudinary.com/drkxqkguu/image/upload/q_auto,f_auto/cards/{$cardIds[0]}.jpg";
}
@endphp
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title }}</title>

    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="lycee, lycee overture, tcg, trading card game, english, japanese, translation, database">

    <meta property="og:title" content="{{ $title }}" />
    <meta property="og:description" content="{{ $description }}" />
    <meta property="og:image" content="{{ $ogImageUrl }}" />
    <meta property="og:image:secure_url" content="{{ $ogImageUrl }}" />
    <meta property="og:image:width" content="{{ $ogWidth }}" />
    <meta property="og:image:height" content="{{ $ogHeight }}" />

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
@yield('beforeContent')
@yield('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</body>
</html>
