<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Forum') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.App = {!! json_encode([
            'csrfToken' => csrf_token(),
            'user' => Auth::user(),
            'signedIn' => Auth::check()
        ]) !!};
    </script>

    <style>
        body {padding-bottom: 50px;}
        .level {display: flex; align-items: center}
        .flex {flex: 1}
        .mr-1 { margin-right: 1em; }
        [v-cloak] { display: none; }
        .ml-a { margin-left: auto; }
        .level-item { margin-right: 1em; }
        .ais-highlight > em { background: yellow; font-style: normal; }
    </style>

    @yield('head')

</head>
<body>
    <div id="app">
        @include('layouts.nav')

        @yield('content')

        <flash message="{{ session('flash') }}"></flash>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    @yield('scripts')
</body>
</html>
