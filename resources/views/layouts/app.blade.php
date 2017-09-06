<!DOCTYPE html>
<html lang="es">
@include('shared.head')
<body id="page-top" class="index">

<header id="a365-header" class="inicio">
    <div class="container">
        <div class="col-xs-3 col-sm-3 col-md-2">
            <a href="{{ url('/') }}"><img id="logo" src="{{asset('images/logo.png')}}"/></a>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-7">
            <div class="search-bar">
                <form class="form-search" method="get" action="{{ url('/search/') }}">
                    <input name="q" type="text" class="form-control fsearch" placeholder="¿Que Buscas?">
                    <input name="w" type="text" class="form-control fsearch ciudad" placeholder="¿Donde?">
                    <button type="submit" class="btn btn-default" style="background: transparent; border: none; padding: 2px;">
                        <span style="font-size: 24px;" class="glyphicon glyphicon-search"></span>
                    </button>
                </form>
            </div>
        </div>
        <div class="col-xs-3 col-sm-3 col-md-3" style="text-align: right; padding-top: 15px;">
            @if (Auth::guest())
            <a href="{{ url('/login') }}"><i class="fa fa-sign-in" aria-hidden="true"></i> Iniciar sesión</a> |
            <a style="white-space: nowrap;" href="{{ url('/register') }}"><i class="fa fa-user" aria-hidden="true"></i> Registrarse</a>
            @else
                Bienvenido, {{ Auth::user()->name }} |
                <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out" aria-hidden="true"></i> Salir
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @endif
        </div>
    </div>
</header>

<div class="banner-home" data-stellar-background-ratio="0.5">
    <div class="container">

    </div>
</div>

<div style="background-color: #eee; margin-bottom: 20px;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="masthead">
                    <nav>
                        <ul class="nav nav-justified">
                            <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> Home</a></li>
                            <li><a href="{{ url('/register') }}"><i class="fa fa-user"></i> Registrate</a></li>
                            <li><a href="#"><i class="fa fa-thumbs-o-up"></i> Mas votados</a></li>
                            <li><a href="#"><i class="fa fa-cutlery"></i> Restaurantes</a></li>
                            <li><a href="#"><i class="fa fa-bed"></i> Hoteles</a></li>
                            <li><a href="#"><i class="fa fa-car"></i> Autos</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

@yield('content')

@include('shared.footer')

<script>
    $(document).ready(function () {
        $.stellar();
        //$('#input-3').rating({displayOnly: true, step: 0.5});

        var config = {
            url: window.location.href,
            ui: {
                flyout: 'top left',
                button_font: false,
                buttonText: 'Compartir',
                icon_font: false
            },
            networks: {
                googlePlus: {enabled: true, url: ''},
                twitter: {enabled: true, url: '', description: ''},
                facebook: {
                    enabled: true,
                    load_sdk: true,
                    url: '',
                    appId: '',
                    title: '',
                    caption: '',
                    description: '',
                    image: ''
                },
                pinterest: {enabled: true, url: '', image: '', description: ''},
                reddit: {enabled: false},
                linkedin: {enabled: false},
                whatsapp: {enabled: false},
                email: {enabled: false}
            }
        }

        var shareButton = new ShareButton('', config);

    });
</script>

</body>
</html>
