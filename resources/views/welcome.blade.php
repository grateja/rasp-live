<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sunrys</title>
        <link rel="shortcut icon" href="{{ asset('img/ico.png') }}" />

        <!-- Fonts -->
        <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet"> -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <style>
        /* body {
            background: url('/img/bg.jpg');
            background-size: cover;
            background-position: left;
        } */
    </style>
    <body>
        <v-app id="app" class="grey lighten-4">
            <main-body />
        </v-app>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
