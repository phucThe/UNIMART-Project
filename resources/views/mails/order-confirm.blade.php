<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        html {
            background: #eeeeee;
        }

        #checkout-products h1,
        #checkout-products h2,
        #checkout-products h3,
        #checkout-products h4,
        #checkout-products h5,
        #checkout-products h6 {
            color: #2B2D42;
            font-weight: 700;
            margin: 0 0 10px;
        }

        #checkout-products a {
            color: #2B2D42;
            font-weight: 500;
            transition: 0.2s color;
            text-decoration: none;
        }

        #checkout-products a:hover,
        #checkout-products a:focus {
            color: #D10024;
            text-decoration: none;
            outline: none;
        }

        #checkout-products table {
            width: 100%;
        }

        #checkout-products table tr td {
            width: 50%;
        }

        #checkout-products .primary-btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #D10024;
            border: none;
            /* border-radius: 40px; */
            color: #fff;
            text-transform: uppercase;
            font-weight: 700;
            text-align: center;
            -webkit-transition: 0.2s all;
            transition: 0.2s all;
        }

        #checkout-products .primary-btn:hover,
        #checkout-products .primary-btn:focus {
            opacity: 0.9;
            color: #fff;
        }

        #checkout-products .cancel-confirm-btn {
            background: #15161D;
        }

        #checkout-products {
            max-width: 600px;
            margin: 0px auto;
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            font-weight: 400;
            color: #333 !important;
            line-height: 24px;
        }

        #checkout-products .container {
            padding: 15px 50px;
            color: #333;
            background: white;
        }

        #checkout-products .body {
            margin-bottom: 30px;
        }

        #checkout-products .logo {
            text-align: center;
            background-color: #15161D;
            border-bottom: 3px solid #D10024;
            padding: 10px 0px;
        }

        #checkout-products .logo a {
            display: inline-block;
            width: 25%;

        }

        #checkout-products .logo a img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        #checkout-products .header {
            margin-bottom: 30px;
        }

        #checkout-products .text-center {
            text-align: center;
        }

        #checkout-products .text-bold {
            font-weight: bold;
        }

        #checkout-products .text-center a {
            display: inline-block;
            color: #fff;
        }

        #checkout-products .order-info {
            margin-bottom: 10px;
        }

        #checkout-products .order-item-wp {
            margin-top: 20px;
        }

        #checkout-products .item-img {
            margin-bottom: 30px;
        }

        #checkout-products .item-img a {
            display: block;
            width: 140px;
            height: 140px;
        }

        #checkout-products .item-img img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        #checkout-products .order-total-price {
            margin: 15px 0px;
        }

        #checkout-products .order-total-price table tr td {
            font-weight: bold;
        }

        #checkout-products .cancel-order {
            margin-top: 30px;
        }
    </style>
</head>


<body>
    <div id="wrapper">
        <div class="logo">
            <a href="">
                <img src="{{ asset('images/logo.png') }}" alt="">
            </a>
        </div>
        <div class="container">
            <div class="header">
                <p>Xin chào <span class="text-bold">{{$shipping_info->fullname}}</span></p>
                <p>
                    Bạn đã đặt đơn hàng <span class="text-bold">#{{$shipping_info->id}}</span> tại hệ thống UNIMART vào ngày {{date('d/m/Y',strtotime($shipping_info->updated_at))}}.
                </p>
                <p>Để hoàn tất quá trình đặt hàng vui lòng nhấn vào nút xác nhận bên dưới để xác nhận đơn hàng.
                    Đơn hàng của bạn sẽ bị huỷ nếu bạn không xác nhận trong vòng một giờ kể từ lúc bạn nhận được email
                    này.
                </p>
                <div class="text-center">
                    <a href="{{ route('order_confirm') . '?active_token=' . $active_token }}" class="primary-btn order-confirm-btn">Xác nhận</a>
                </div>
            </div>
            <hr>
            <div class="body">
                <div class="order-info">
                    <h3>Thông tin đơn hàng</h3>
                    <table>
                        <tr>
                            <td>Mã đơn hàng:</td>
                            <td>#{{$shipping_info->id}}</td>
                        </tr>
                        <tr>
                            <td>Ngày đặt hàng:</td>
                            <td>{{date('d/m/Y H:i:s',strtotime($shipping_info->updated_at))}}</td>
                        </tr>
                    </table>

                </div>
                <div class="order-wp">
                    @if ($order_products->count() > 0)
                    @foreach ($order_products as $item)
                    @php
                    if(is_null($item->productCatName)){
                    $productCatSlug = 'san-pham-khac';
                    }else {
                    $productCatSlug = $item->productCatSlug;
                    }
                    @endphp
                    <div class="order-item-wp">
                        <div class="item-img">
                            <a href="{{route('product_detail',[ $productCatSlug, $item->slug_convert($item->productName),$item->product_id])}}">
                                <img src="{{ $item->img_path }}" alt="">
                            </a>
                        </div>
                        <table>
                            <tr>
                                <td colspan="2">
                                    <a href="{{route('product_detail',[ $productCatSlug, $item->slug_convert($item->productName),$item->product_id])}}" class="text-bold">{{$item->productName}}</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Màu:</td>
                                <td>{{ $item->product_color }}</td>
                            </tr>
                            <tr>
                                <td>Số lượng:</td>
                                <td>{{ $item->qty }}</td>
                            </tr>
                            <tr>
                                <td>Giá:</td>
                                <td>{{ number_format($item->price, 0, '') . 'đ' }}</td>
                            </tr>
                        </table>
                        <hr>
                    </div>
                    @endforeach

                    @endif



                </div>
                <div class="order-total-price">
                    <table>
                        <tr>
                            <td>Tổng tiền:</td>
                            <td>{{ number_format($shipping_info->total, 0, '') . 'đ' }}</td>
                        </tr>
                    </table>
                </div>
                <hr>
                <div class="cancel-order">
                    <h3>Bước tiếp theo</h3>
                    <div class="cancel-order-body">
                        <p>Bạn muốn huỷ đơn hàng?</p>
                        <p>
                            Bạn có thể xác nhận huỷ đơn hàng ở bên dưới. Hệ thống UNIMART sẽ nhận được yêu cầu huỷ đơn
                            hàng của bạn.
                        </p>
                        <p>Chúc bạn có những trải nghiệm tuyệt vời khi mua hàng tại UNIMART.</p>
                        <div class="text-center">
                            <a href="{{ route('order_cancel') . '?active_token=' . $active_token }}" class="primary-btn cancel-confirm-btn">Huỷ đơn hàng</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>
