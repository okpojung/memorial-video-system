<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>추모영상</title>
    @vite(['resources/scss/app.scss','resources/css/app.css'])
</head>
@vite('resources/js/app.js')
{{--<div id="app">--}}
<body class="2xl:container mx-auto ">
    <div class="flex items-center justify-between gap-x-6 bg-gray-900 px-6 py-2.5 sm:pr-3.5 lg:pl-8">
        <p class="text-sm leading-6 text-white">
            <a href="#">
                <strong class="font-semibold">{{env('APP_PLACE')}}</strong>
            </a>
        </p>
    </div>

    @include('sweetalert::alert')
    @yield('content')
</body>
{{--</div>--}}


</html>

<style>
    .form-btn{
        background:#efece7;
        color:#6f6f6f;
    }
</style>
