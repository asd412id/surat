<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title }} | {{ env('APP_NAME') }}</title>
  <link rel="icon" href="{{ env('APP_ICON',asset('img/logo.png')) }}">
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
</head>

<body class="hold-transition login-page dark-mode">
  <div class="login-box">
    <div class="login-logo">
      <a class="h4" href="{{ route('login') }}">
        <img src="{{ env('APP_ICON',asset('img/logo.png')) }}" class="img-fluid" alt="">
      </a>
    </div>
    <div class="card">
      <div class="card-body login-card-body">
        @if ($errors->any())
        <p class="login-box-msg text-danger font-weight-bold">{{ $errors->all()[0] }}</p>
        @else
        <p class="login-box-msg">Silahkan masuk untuk mengatur sistem</p>
        @endif
        <form action="{{ route('login.process') }}" method="post">
          @csrf
          <div class="input-group mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" autofocus required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-olive">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">
                  Ingat Sesi Login
                </label>
              </div>
            </div>
            <div class="col-4">
              <button type="submit" class="btn bg-olive btn-block"><i class="fas fa-door-open fa-sm fa-fw"></i>
                Masuk</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>