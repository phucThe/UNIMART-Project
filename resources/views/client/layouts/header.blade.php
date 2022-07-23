<!-- MAIN HEADER -->
<div id="header">
    <div id="head-body" class="clearfix">
        <div class="wp-inner">
            <div class="row">
                <!-- LOGO -->
                <div class="col-md-3">
                    <div class="header-logo">
                        <a href="{{ url('/home') }}" class="logo">
                            <img src="{{asset('images/logo.png')}}" alt="">
                        </a>
                    </div>
                </div>
                <!-- /LOGO -->

                <!-- SEARCH BAR -->
                <div class="col-md-7">
                    <div class="header-search">
                        <form class="d-flex" action="{{route('product')}}">
                            {{-- <select name="search_for" class="input-select">
                                <option value="0" hidden>Tìm theo</option>
                                <option value="san-pham">Sản phẩm</option>
                                <option value="bai-viet">Bài viết</option>
                            </select> --}}
                            <input class="input" name="keyword" placeholder="Nhập từ khoá tìm kiếm"
                                value="{{ request()->input('keyword') }}">
                            <button class="search-btn" name="search_btn">Tìm kiếm</button>
                        </form>
                    </div>
                </div>
                <!-- /SEARCH BAR -->
                <div id="action-wp" class="col-md-2 d-flex">
                    <div id="advisory-wp">
                        <span class="title">Tư vấn</span>
                        <span class="phone">09xx.xxx.xxx</span>
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
                                                    <a href="{{ $product->options->detail_url }}" title=""
                                                        class="product-name">{{ $product->name }}</a>
                                                    <span
                                                        class="price">{{ number_format($product->total, 0, '') . 'đ' }}</span>
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
