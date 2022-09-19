<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'GreenTech') }}</title>

  <!-- Font Awesome Solid + Brands -->
  <script defer src="/js/all.min.js"></script>
  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

  <!-- Scripts -->
  <script src="{{ mix('js/app.js') }}" defer></script>

</head>

<body style="height: 100%;background-color: #e9ecef;">
  <div id="app" class="wrapper">
    <div class="lockscreen-wrapper">
      <div class="lockscreen-logo">
        <a href="#" style="font-size: 30px"><span style="font-weight: 600">GREEN</span>TECH</a>
      </div>

      <div class="lockscreen-name">{{ Auth::user()->name }}</div>

      <div class="lockscreen-item">
        <div class="lockscreen-image">
          <img src="/img/user.jpg" alt="User Image" />
        </div>

        <form class="lockscreen-credentials" method="POST" action="{{request()->route()->uri}}">
          @csrf
          <div class="input-group">
            <input type="password" class="form-control" placeholder="password" name="password" />
            <div class="input-group-append">
              <button type="submit" class="btn">
                <i class="fas fa-arrow-right text-muted"></i>
              </button>
            </div>
            @if(isset($erreur))
            <span class="error invalid-feedback">{{ $erreur }}</span>
            @endif
          </div>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
