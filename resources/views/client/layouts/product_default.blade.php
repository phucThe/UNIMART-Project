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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
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
            <form action="">
                <div id="header-wp">
                    <!-- MAIN HEADER -->
                    <div id="header">
                        <div id="head-body" class="clearfix">
                            <div class="wp-inner">
                                <div class="row">
                                    <!-- LOGO -->
                                    <div class="col-md-3">
                                        <div class="header-logo">
                                            <a href="{{ url('/home') }}" class="logo">
                                                <img src="{{ asset('images/logo.png') }}" alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <!-- /LOGO -->

                                    <!-- SEARCH BAR -->
                                    <div class="col-md-7">
                                        <div class="header-search">
                                            <div class="d-flex">

                                                <input class="input" name="keyword" placeholder="Nhập từ khoá tìm kiếm" value="{{ request()->input('keyword') }}">
                                                <button class="search-btn" name="search_btn">Tìm kiếm</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /SEARCH BAR -->
                                    <div id="action-wp" class="col-md-2 d-flex">
                                        <div id="advisory-wp">
                                            <span class="title">Tư vấn</span>
                                            <span class="phone">0987.654.321</span>
                                        </div>
                                        <div id="btn-respon" class="">
                                            <i class="fa fa-bars d-block" aria-hidden="true"></i>
                                        </div>
                                        <a href="{{ route('cart_list') }}" title="giỏ hàng" id="cart-respon-wp">
                                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                            <span id="num">{{ Cart::content()->count() }}</span>
                                        </a>
                                        <div id="cart-wp" class="fl-right">
                                            <div id="btn-cart">
                                                <a href="{{ route('cart_list') }}">
                                                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                                </a>
                                                <span id="num">{{ Cart::content()->count() }}</span>
                                            </div>
                                            <div id="dropdown">
                                                <div class="cart-info">
                                                    <p class="desc">Có <span>{{ Cart::content()->count() }} sản phẩm</span> trong giỏ
                                                        hàng
                                                    </p>
                                                    <ul class="list-cart">
                                                        @if (Cart::content()->count() > 0)
                                                        @foreach (Cart::content() as $product)
                                                        <li class="clearfix">
                                                            <a href="{{ $product->options->detail_url }}" title="" class="thumb fl-left">
                                                                <img src="{{ $product->options->img_path }}" alt="">
                                                            </a>
                                                            <div class="info fl-right">
                                                                <a href="{{ $product->options->detail_url }}" title="" class="product-name">{{ $product->name }}</a>
                                                                <span class="price">{{ number_format($product->total, 0, '') . 'đ' }}</span>
                                                                <span class="qty">Số lượng:
                                                                    <span>{{ $product->qty }}</span></span>
                                                            </div>
                                                        </li>
                                                        @endforeach
                                                        @endif

                                                    </ul>
                                                    <div class="total-price d-flex">
                                                        <span class="title">Tổng:</span>
                                                        <span class="price">{{ Cart::total(0, '') . 'đ' }}</span>
                                                    </div>

                                                </div>
                                                <div class="action-cart d-flex">
                                                    <a href="{{ route('cart_list') }}" title="Giỏ hàng" class="view-cart">Giỏ hàng</a>
                                                    @if (Cart::content()->count() > 0)
                                                    <a href="{{route('checkout_show')}}" title="Thanh toán" class="checkout">Thanh toán</a>
                                                    @else
                                                    <a href="{{ route('cart_list') }}" title="Giỏ hàng" class="checkout">Thanh toán</a>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /MAIN HEADER -->
                    </header>
                    <!-- /HEADER -->
                </div>
                <div id="navigation-wp">
                    @include('client.layouts.navigation')
                </div>
                <div id="content-wp">
                    @yield('content')
                </div>


            </form>
            <div id="footer-wp">
                @include('client.layouts.footer')
            </div>
        </div>
        <div id="menu-responsive-wp">
            @include('client.layouts.menu_respon')
        </div>
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
