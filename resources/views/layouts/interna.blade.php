<!DOCTYPE html>
<html lang="es">
@include('shared.head')
<body id="page-top" class="index">

<header id="a365-header" class="global">
    <div class="container">
        <div class="col-xs-3 col-sm-3 col-md-2">
            <a href="{{ url('/') }}"><img id="logo" src="{{asset('images/logo.png')}}"/></a>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-7">
            <div class="search-bar">
                <form class="form-search" method="get" action="{{ url('/search/') }}">
                    <input name="q" value="{{ $_REQUEST['q'] or '' }}" type="text" class="form-control fsearch" placeholder="¿Que Buscas?">
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


@yield('content')
@include('shared.footer')

    <!-- JavaScripts -->
    <script src="{{ asset('js/slick.min.js') }}"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}

<script>
    $(document).ready(function() {

        $('.slider').slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 1,
            centerMode: false,
            variableWidth: true,
            nextArrow: '<div class="slick-next" style="height: 200px; background-color: #EDEDED;"><i class="fa fa-arrow-right" style="margin-top: 110px;"></i></div>',
            prevArrow: '<div class="slick-prev" style="height: 200px; background-color: #EDEDED;"><i class="fa fa-arrow-left" style="margin-top: 110px;"></i></div>'
        });

        var config = {
            url: window.location.href,
            ui: {
                flyout: 'top left',
                button_font: false,
                buttonText: 'Compartir',
                icon_font: false
            },
            networks: {
                googlePlus: { enabled: true, url: ''},
                twitter: { enabled: true, url: '', description: ''},
                facebook: { enabled: true, load_sdk: true, url: '', appId: '', title: '', caption: '', description: '', image: ''},
                pinterest: { enabled: true, url: '', image: '', description: ''},
                reddit: { enabled: false},
                linkedin: { enabled: false},
                whatsapp: { enabled: false},
                email: { enabled: false}
            }
        }

        var shareButton = new ShareButton('', config);

    });
</script>

</body>
</html>
