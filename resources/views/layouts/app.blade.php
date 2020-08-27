<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="sidebar-mini layout-fixed layout-navbar-fixed text-sm">
    <div id="app">
        <div class="wrapper">
          <!-- navbar -->
              @include('layouts.includes.navbar')
          <!-- end navar -->
          <!-- sidebar -->
              @include('layouts.includes.sidebar')
          <!-- end sidebar -->

          <!-- content-wrapper -->
          <div class="content-wrapper">
             @yield('content')
          </div>
          <!-- end content-wrapper -->
          <!-- footer -->
          <footer class="main-footer">
              <strong>Copyright © 2020 <a href="/">SAF</a>.</strong>
                All rights reserved.
          </footer>
          <!-- footer -->
        </div>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('js')
</body>
</html>
