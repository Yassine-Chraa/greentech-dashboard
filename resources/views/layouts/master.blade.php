<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>GreenTech</title>

  <!-- Font Awesome Solid + Brands -->
  <script defer src="/js/all.min.js"></script>
  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

  <!-- Scripts -->
  <script src="js/chart.min.js"></script>
  <script src="js/echart.min.js"></script>
  <script src="{{ mix('js/app.js') }}" defer></script>

</head>

<body>
  <div id="app" class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button" id="sidebarClose"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a href="/lockScreen" class="nav-link">
            <i class="fa-solid fa-lock"></i>
          </a>
        </li>
        <Notifications />
      </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <a href="/home" class="brand-link">
        <img src="/img/GreenTech.webp" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">GreenTech</span>
      </a>
      <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="/img/user.jpg" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <span class="d-block" style="color: rgba(255, 255, 255, 0.8);">{{ Auth::user()->name }}</span>
          </div>
        </div>
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <router-link to="/home" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt blue"></i>
                <p>
                  Tableau de board
                </p>
              </router-link>
            </li>
            @if(Auth::user()->isAdmin == 1)
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fa fa-cog green"></i>
                <p>
                  Gestion
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/clients" class="nav-link">
                    <i class="fa-solid fa-users"></i>
                    <p>Clients</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/commandes" class="nav-link">
                    <i class="fa-solid fa-boxes-stacked fa-shop"></i>
                    <p>Commandes</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/produits" class="nav-link">
                    <i class="fa-solid fa-boxes-stacked "></i>
                    <p>Produits</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/categories" class="nav-link">
                    <i class="fa-solid fa-boxes-stacked "></i>
                    <p>Categories</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link to="/messages" class="nav-link">
                    <i class="fa-solid fa-envelope"></i>
                    <p>Messages</p>
                  </router-link>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <router-link to="/users" class="nav-link">
                <i class="nav-icon fa-solid fa-user"></i>
                <p>
                  Utilisateurs
                </p>
              </router-link>
            </li>
            @endif

            <li class="nav-item">
              <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                <i class="nav-icon fa fa-power-off red"></i>
                <p>
                  {{ __('Logout') }}
                </p>
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </li>
          </ul>
        </nav>

      </div>
    </aside>
    <router-view isadmin="{{ Auth::user()->isAdmin }}"></router-view>

    <div id="sidebar-overlay" onclick="document.getElementById('sidebarClose').click()"></div>
  </div>

  <script>
    var api_token = "{{ Session()->get('api_token') }}";
    localStorage.setItem('api_token', api_token)
  </script>
</body>

</html>
