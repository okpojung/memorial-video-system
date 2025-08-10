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

    <body class="2xl:container mx-auto flex">

    @include('sweetalert::alert')
    @include ('layouts.header')
    <div class="w-full">
        @include ('layouts.mobileHeader')
        @yield('content')
    </div>
    </body>
</html>
