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
<body class="hold-transition login-page">
    <div id="app">
        <div class="login-box">
          <div class="login-logo">
            <a href="/l"><b>SAF</b></a>
          </div>
          <!-- /.login-logo -->
          <div class="card">
            <div class="card-body login-card-body">
              <p class="login-box-msg">Ingresar credenciales</p>

              <form action="{{ route('login') }}" method="post" autocomplete="off">
                @csrf
                <div class="input-group mb-3">
                  <input type="email" class="form-control" name="email" placeholder="Correo electrónico">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-envelope"></span>
                    </div>
                  </div>
                </div>
                <div class="input-group mb-3">
                  <input type="password" class="form-control" name="password" placeholder="Contraseña">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-lock"></span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <!-- /.col -->
                  <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
                  </div>
                  <!-- /.col -->
                </div>
              </form>

              <p class="mb-1">
                <a href="forgot-password.html">Olvidó su contraseña?</a>
              </p>
            </div>
            <!-- /.login-card-body -->
          </div>
        </div>
<!-- /.login-box -->
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    </div>
</body>
</html>