<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body class="max-w-screen-sm mx-auto antialiased bg-white h-full font-sans overflow-hidden relative">
    {{ $slot }}

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>
