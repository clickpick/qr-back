<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Game map</title>
    <link href="{{ mix('css/face.css') }}" rel="stylesheet">
</head>
<body>

<main class="wrapper">
    <div class="wrapper-bg"
         style="background-image: url({{\App\Project::getActive()->getFirstMedia('banner')->getFullUrl('card')}})"></div>
    <div id="map" style="height: 100vh; width: 100%; position: relative"></div>
</main>

<script src="https://api-maps.yandex.ru/2.1/?apikey={{config('services.yandex.maps.key')}}&lang=ru_RU" type="text/javascript"></script>
<script src="{{ mix('js/face.js') }}"></script>
</body>
</html>
