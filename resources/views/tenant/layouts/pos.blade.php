@php
$path = explode('/', request()->path()); $path[1] = (array_key_exists(1, $path)> 0)?$path[1]:''; $path[2] = (array_key_exists(2,
$path)> 0)?$path[2]:''; $path[0] = ($path[0] === '')?'documents':$path[0];
@endphp
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

        <link rel="stylesheet" href="{{ asset('porto-light/vendor/font-awesome/css/fontawesome-all.min.css') }}"/>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('porto-light/vendor/bootstrap/css/bootstrap.css') }}"/>
        <link rel="stylesheet" href="{{ asset('porto-light/css/theme.css') }}"/>
        <link rel="stylesheet" href="{{ asset('porto-light/css/custom.css') }}"/>

        <title>Punto de Venta</title>

        @yield('head')
    </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-info py-0">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <a class="navbar-brand" href="{{route('tenant.dashboard')}}">
                @if($vc_company->logo)
                    <img src="{{ asset('storage/uploads/logos/'.$vc_company->logo) }}" alt="Logo"  class="logo-sm" style="height: 30px" />
                @else
                    <img src="{{asset('logo/700x300.jpg')}}" alt="Logo" />
                @endif
            </a>
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ ($path[0] === 'pos')?'active':'' }}" href="{{ route('tenant.pos.index') }}">POS</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link {{ ($path[0] === 'pos-sells')?'active':'' }}" href="{{ route('tenant.pos.sales') }}">Ventas</a>
                </li> --}}
                @if($vs_pos_configuration->cash_shifts)
                    <li class="nav-item">
                        <a class="nav-link {{ ($path[0] === 'pos-shifts')?'active':'' }}" href="{{ route('tenant.pos_shifts.index') }}">Turnos</a>
                    </li>
                @endif                
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tenant.pos.configuration.index')}}">Configuración</a>
                </li>
            </ul>
            <ul class="navbar-nav my-2 my-lg-0 mr-3">
                <li class="nav-item">
                    <a class="nav-link active"><i class="fa fa-list-alt"></i> {{$pos_station->name}} |</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span>{{ $vc_user->name }}</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="#">Salir</a>
                    </div>
                </li>
            </ul>
            {{-- <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form> --}}
        </div>
    </nav>
    <section class="body">
        <div id="main-wrapper">
            @yield('content')
        </div>
    </section>

    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    {{-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    {{-- <script src="{{ asset('porto-light/vendor/jquery/jquery.js')}}"></script> --}}
    <script src="{{ asset('porto-light/vendor/jquery-browser-mobile/jquery.browser.mobile.js')}}"></script>

  </body>
</html>