<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Amarillas 365 - @yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}">
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Lato:400,700,400italic'>
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Montserrat:400,700'>
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Kaushan+Script'>
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic'>
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700'>
    <link rel='stylesheet' type='text/css' href='{{ asset('font-awesome/css/font-awesome.min.css') }}'>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootflat.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/amarillas365.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/rateit.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/share-button.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/slick-theme.css') }}">

    <script src="{{ asset('js/jquery-2.2.2.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.stellar.min.js') }}"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="{{ asset('js/a365.js') }}"></script>
    <script src="{{ asset('js/rateit.min.js') }}"></script>
    <script src="{{ asset('js/share-button.min.js') }}"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <meta name="google-site-verification" content="w9NgrzouA9tEvDxcdaTXbKoaD3mhNPxwgvjmr9MrlKE" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>

</head>