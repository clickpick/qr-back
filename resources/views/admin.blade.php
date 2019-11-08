<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1,width=device-width,height=device-height,viewport-fit=cover,shrink-to-fit=no,uc-fitscreen=yes,target-densityDpi=device-dpi">
        <title>QR Game</title>
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="admin"></div>
        <script src="{{ mix('js/admin.js') }}"></script>
    </body>
</html>
