<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/0.11.1/trix.css">
    
    @yield('styles')
</head>
<script>
    window.App = {!! json_encode([
        'signedIn' => Auth::check(),
        'user' => Auth::user()
    ])!!};
</script>
<style>
body{
    padding-bottom: 100px;
}
.level{
    display: flex; align-items: center;
}
.flex{
    flex:1;
}
.mr-1{margin-right: 1em;}
.ml-a{margin-left:auto; }
.card-success{background-color: greenyellow;}
[v-cloak]{display: none}
</style>
<body >
    <div id="app">
       @include('layouts.nav')

        <main class="py-4">
            @yield('content')
        </main>

        <flash message="{{ session('flash') }}"></flash>
    </div>
</body>
<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')
</html>
