@extends('client.layouts.default')
@section('content')
    <div id="main-content-wp">
        <div class="wp-inner">
            <div class="text-center">
                <h3>
                    Bạn đã đặt hàng thành công
                </h3>
                <p>Nhân viên chăm sóc sẽ liên hệ với bạn sớm nhất để xác nhận đơn hàng</p>
            </div>
            <h5>Mã đơn hàng: #{{$order_info->id}}</h5>
            <table class="table" id="customer-info">
                <tr>
                    <td>
                        Tên khách hàng:
                    </td>
                    <td>
                        {{$order_info->fullname}}
                    </td>
                </tr>
                <tr>
                    <td>
                        Email:
                    </td>
                    <td>
                        {{$order_info->email}}
                    </td>
                </tr>
                <tr>
                    <td>
                        Địa chỉ:
                    </td>
                    <td>
                        {{$order_info->address}}
                    </td>
                </tr>
                <tr>
                    <td>
                        Số điện thoại:
                    </td>
                    <td>
                        {{$order_info->phone}}
                    </td>
                </tr>
                <tr>
                    <td>
                        Ghi chú:
                    </td>
                    <td>
                        {{$order_info->note}}
                    </td>
                </tr>
            </table>
            <div id="checkout-products">
                @if ($order_products->count() > 0)
                    @php
                        $i = 1;
                    @endphp
                    <table class="table">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Ảnh</td>
                                <td>Tên sản phẩm</td>
                                <td>Giá</td>
                                <td>Số lượng</td>
                                <td>Màu</td>
                                <td>Thành tiền</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order_products as $item)
                                @php
                                    if (is_null($item->productCatName)) {
                                        $productCatSlug = 'san-pham-khac';
                                    } else {
                                        $productCatSlug = $item->productCatSlug;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        <div class="thumb">
                                            <a
                                                href="{{ route('product_detail', [$productCatSlug, $item->slug_convert($item->productName), $item->product_id]) }}">
                                                <img src="{{ $item->img_path }}" alt="">
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('product_detail', [$productCatSlug, $item->slug_convert($item->productName), $item->product_id]) }}"
                                            class="text-bold">{{ $item->productName }}</a>
                                    </td>
                                    <td>{{ number_format($item->price, 0, '') . 'đ' }}</td>
                                    <td>
                                        {{ $item->qty }}
                                    </td>
                                    <td>{{ $item->product_color }}</td>
                                    <td>{{ number_format($item->sub_total, 0, '') . 'đ' }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="6">Tổng tiền:</td>
                                <td>{{ number_format($order_info->total, 0, '') . 'đ' }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
