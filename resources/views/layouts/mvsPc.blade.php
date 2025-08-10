<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="/images/logo/simple_logo.svg">
    <title>추모영상</title>
    @vite(['resources/scss/app.scss','resources/css/app.css'])

</head>
<script src="https://unpkg.com/alpinejs@3.11.1/dist/cdn.min.js" defer></script>

@vite('resources/js/app.js')

    <body id="fullScreen" class="w-full bg-gray-900 relative">
    @include('sweetalert::alert')
    @include ('layouts.mvsPcHeader')
    <div class="w-full">
{{--        <div id="app">--}}
{{--        </div>--}}
{{--        @include ('layouts.mobileHeader')--}}
        @yield('content')
    </div>
    </body>
</html>
