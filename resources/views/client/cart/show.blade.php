@extends('client.layouts.default')
@section('content')
    <div id="breadcrumb-wp">
        <!-- BREADCRUMB -->
        <div id="breadcrumb" class="section">
            <!-- container -->
            <div class="container">
                <!-- row -->
                <div class="row">
                    <div class="col-md-12">
                        <ul class="breadcrumb-tree">
                            <li>
                                <a href="{{ url('/home') }}">Home</a>
                            </li>
                            <li class="active">Giỏ hàng</li>
                        </ul>
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /BREADCRUMB -->
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div id="main-content-wp" class="cart-page">
        <div id="wrapper" class="wp-inner clearfix">
            @if (Cart::content()->count() > 0)
                <div class="section" id="info-cart-wp">
                    <div class="section-detail table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>Mã sản phẩm</td>
                                    <td>Ảnh sản phẩm</td>
                                    <td>Tên sản phẩm</td>
                                    <td>Màu</td>
                                    <td>Giá sản phẩm</td>
                                    <td>Số lượng</td>
                                    <td>Thành tiền</td>
                                    <td>Tác vụ</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (Cart::content() as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            <a href="" title="" class="thumb">
                                                <img src="{{ $product->options->img_path }}" alt="">
                                            </a>
                                        </td>
                                        <td>
                                            <a href="" title="" class="name-product">{{ $product->name }}</a>
                                        </td>
                                        <td>{{ $product->options->color }}</td>
                                        <td>{{ number_format($product->price, 0, '') . 'đ' }}</td>
                                        <td>
                                            <div class="d-flex num-order-container">
                                                <a title="" class="minus" id="minus"><i
                                                        class="fa fa-minus d-block"></i></a>
                                                <input type="number" class="no-spin-input-number num-order input p-0"
                                                    name="num_order[]" data-id="{{ $product->rowId }}"
                                                    value="{{ $product->qty }}" min="1" id="num-order">
                                                <a title="" class="plus" id="plus"><i
                                                        class="fa fa-plus d-block"></i></a>
                                            </div>
                                        </td>
                                        <td class="sub-total">{{ number_format($product->total, 0, '') . 'đ' }}</td>
                                        <td>
                                            <a href="{{ route('cart_remove', $product->rowId) }}" title=""
                                                class="del-product">
                                                <i class="fa fa-trash"></i>
                                                Xoá
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7">
                                        <div class="clearfix">
                                            <p id="total-price" class="fl-right">Tổng giá:
                                                <span>{{ Cart::total(0, '') . 'đ' }}</span>
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="7">
                                        <div class="clearfix">
                                            <div class="fl-right">
                                                <a href="{{ route('checkout_show') }}" title="Thanh toán"
                                                    class="primary-btn">Thanh toán</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="section" id="action-cart-wp">
                    <div class="section-detail">
                        <p class="title">Click vào <span>“Cập nhật giỏ hàng”</span> để cập nhật số lượng. Nhập vào số
                            lượng
                            <span>0</span> để xóa sản phẩm khỏi giỏ hàng. Nhấn vào thanh toán để hoàn tất mua hàng.
                        </p>
                        <a href="{{ route('product') }}" title="" id="buy-more">Mua tiếp</a><br />
                        <a href="{{ route('cart_destroy') }}" title="" id="delete-cart">Xóa giỏ hàng</a>
                    </div>
                </div>
            @else
                @include('client.cart.empty_cart')
            @endif
        </div>
    </div>

@endsection
