<!DOCTYPE html>
<html>

<head>
    <title>UNIMART STORE</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- font -->
    <link rel="stylesheet" href="{{ asset('plugin/fontawesome/css/all.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <!-- css -->
    <link href="{{ asset('css/reset.css') }}" rel="stylesheet" type="text/css" />
    {{-- <link href="{{asset('css/carousel/owl.carousel.css')}}" rel="stylesheet" type="text/css" /> --}}
    {{-- <link href="{{asset('css/carousel/owl.theme.css')}}" rel="stylesheet" type="text/css" /> --}}
    <link href="{{ asset('css/carousel/slick.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/carousel/slick-theme.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/client-main.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet" type="text/css" />

    <!-- javascript -->
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/elevatezoom-master/jquery.elevatezoom.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/carousel/slick.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/main.js') }}" type="text/javascript"></script>
</head>

<body>
    <div id="site">
        <div id="container">
            <div id="header-wp">
                @include('client.layouts.header')
            </div>
            <div id="navigation-wp">
                @include('client.layouts.navigation')
            </div>
            <div id="content-wp">
                @yield('content')
            </div>
            <div id="footer-wp">
                @include('client.layouts.footer')
            </div>
        </div>
        <div id="menu-responsive-wp">
            @include('client.layouts.menu_respon')
        </div>
        <div id="btn-top"><span><i class="fa-solid fa-angle-up"></i></span></div>
    </div>

    @if (session('notification'))
        <div class="bg-box"></div>
        <div class="notification-box">
            <div class="mb-4">
                <span class="empty-cart-icon "><i class="fa-solid fa-circle-check"></i></span>
            </div>
            <h1>{{ session('notification') }}</h1>
        </div>
    @endif

    @if (session('alert'))
        <div class="bg-box"></div>
        <div class="alert-box">
            <div class="mb-4">
                <span class="empty-cart-icon "><i class="fa-solid fa-face-frown-open"></i></span>
            </div>
            <h1>{{ session('alert') }}</h1>
        </div>
    @endif

</body>

</html>
