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
                            <li class="active">Thanh toán</li>
                        </ul>
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /BREADCRUMB -->
    </div>
    <div id="main-content-wp" class="checkout-page">
        <form action="{{ route('order_add') }}" method="POST">
            @csrf
            <div id="wrapper" class="wp-inner clearfix">
                <div class="section" id="customer-info-wp">
                    <div class="section-head">
                        <h1 class="section-title">Thông tin khách hàng</h1>
                    </div>
                    <div class="section-detail">
                        @php
                            $customer_fullname = Cookie::get('customer_fullname');
                            $customer_email = Cookie::get('customer_email');
                            $customer_address = Cookie::get('customer_address');
                            $customer_phone = Cookie::get('customer_phone');
                        @endphp
                        <div class="form-row clearfix">
                            <div class="form-col fl-left">
                                <label for="fullname">Họ tên</label>
                                <input type="text" name="fullname" id="fullname"
                                    @if (is_null($customer_fullname)) value="{{ old('fullname', request()->fullname) }}"
                                @else
                                    value="{{ $customer_fullname }}" @endif
                                    class="input">
                                @error('fullname')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col fl-right">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email"
                                    @if (is_null($customer_email)) value="{{ old('email', request()->email) }}"
                                @else
                                    value="{{ $customer_email }}" @endif
                                    class="input">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row clearfix">
                            <div class="form-col fl-left">
                                <label for="address">Địa chỉ</label>
                                <input type="text" name="address" id="address"
                                    @if (is_null($customer_address)) value="{{ old('address', request()->address) }}"
                                @else
                                    value="{{ $customer_address }}" @endif
                                    class="input">
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col fl-right">
                                <label for="phone">Số điện thoại</label>
                                <input type="tel" name="phone" id="phone"
                                    @if (is_null($customer_phone)) value="{{ old('phone', request()->phone) }}"
                                @else
                                    value="{{ $customer_phone }}" @endif
                                    class="input" maxlength="11">
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <label for="notes">Ghi chú</label>
                                <textarea name="note" class="input">{{ old('note', request()->note) }}</textarea>
                                @error('note')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
                <div class="section" id="order-review-wp">
                    <div class="section-head">
                        <h1 class="section-title">Thông tin đơn hàng</h1>
                    </div>
                    <div class="section-detail">
                        <table class="shop-table">
                            <thead>
                                <tr>
                                    <td>Sản phẩm</td>
                                    <td>Tổng</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (Cart::content() as $product)
                                    <tr class="cart-item">
                                        <td class="product-name">{{ $product->name }}<strong class="product-quantity">x
                                                {{ $product->qty }}</strong></td>
                                        <td class="product-total">{{ number_format($product->total, 0, '') . 'đ' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="order-total">
                                    <td>Tổng đơn hàng:</td>
                                    <td><strong class="total-price">{{ Cart::total(0, '') . 'đ' }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div id="payment-checkout-wp">
                            <div id="payment_methods">
                                <div class="input-radio">
                                    <input type="radio" name="payment" id="payment-home" checked>
                                    <label for="payment-home">
                                        <span></span>
                                        Thanh toán khi nhận hàng
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="place-order-wp clearfix">
                            <button type="submit" name="add_bill" value="add_bill" class="fl-right primary-btn">Đặt
                                hàng</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
