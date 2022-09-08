@extends('client.layouts.default')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div id="breadcrumb-wp">
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
                            <li><a href="{{ route('product') }}">Sản phẩm</a></li>
                            @if (is_null($product->product_cat_id))
                                <li>
                                    <a href="{{ route('product', 'san-pham-khac') }}">
                                        Khác
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('product', $product->product_category()->first()->slug) }}">
                                        {{ $product->product_category()->first()->name }}
                                    </a>
                                </li>
                            @endif
                            <li class="active">{{ $product->name }}</li>
                        </ul>
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /BREADCRUMB -->
    </div>
    <div id="main-content-wp" class="clearfix detail-product-page pb-0">
        <div class="wp-inner">
            <div class="main-content fl-right">
                <div class="section" id="detail-product-wp">
                    <div class="section-detail d-flex flex-row flex-wrap">
                        <div class="thumb-wp position-relative d-flex">
                            <div class="list-thumb-container">
                                <div id="list-thumb" class="vertical-slider">
                                    @foreach ($product_thumb_list as $thumb)
                                        <a href="" data-image="{{ $thumb->img_path }}"
                                            data-zoom-image="{{ $thumb->img_path }}">
                                            <img src="{{ $thumb->img_path }}" />
                                        </a>
                                    @endforeach
                                </div>
                                <div class="slider-nav-col prev">
                                    <button class="slider-prev"><i class="fa-solid fa-angle-up"></i></button>
                                </div>
                                <div class="slider-nav-col next">
                                    <button class="slider-next"><i class="fa-solid fa-angle-down"></i></button>
                                </div>
                            </div>
                            <div title="Ảnh bìa" id="main-thumb">
                                <a href="{{ $product_thumb_list[0]->img_path }}" class="main-thumb-container">
                                    <img id="zoom" @if ($product->status == 2) class="unzoom" @endif
                                        src="{{ $product_thumb_list[0]->img_path }}"
                                        data-zoom-image="{{ $product_thumb_list[0]->img_path }}" />
                                </a>

                            </div>
                            @if ($product->status == 2)
                                <div class="white-box-bg"></div>
                                <div class="out-of-stock-nortification">
                                    <span>Hết</span>
                                    <span>hàng</span>
                                </div>
                            @endif
                        </div>

                        <div class="info">
                            <h3 class="product-name">{{ $product->name }}</h3>
                            <div class="product-price">
                                <h3 class="price">{{ number_format($product->price, 0, '') . 'đ' }}</h3>
                            </div>

                            <div class="product-status">
                                <span class="title">Tình trạng: </span>
                                @if ($product->status == 0)
                                    <span class="status">Riêng tư</span>
                                @endif
                                @if ($product->status == 1)
                                    <span class="status">Còn hàng</span>
                                @endif
                                @if ($product->status == 2)
                                    <span class="status">Hết hàng</span>
                                @endif
                            </div>
                            <div class="product-brand">
                                <span class="title">Thương hiệu: </span>
                                <a href="" class="brand">
                                    <span>
                                        @if ($product->brand == null)
                                            {{ 'Không có thương hiệu' }}
                                        @else
                                            {{ $product->brand->name }}
                                        @endif
                                    </span>

                                </a>
                            </div>

                            <form action="{{ route('cart_add', $product->id) }}" method="POST">
                                @csrf

                                @if ($product->product_colors()->count() > 0)
                                    <div class="product-color">
                                        <div class="color-list-container">
                                            <span class="title">Màu:</span>
                                            <ul class="d-flex flex-wrap color-list">
                                                @foreach ($color_list as $color)
                                                    <li>
                                                        <label>
                                                            <input type="radio" name="product_color"
                                                                value="{{ $color->color_id }}"
                                                                @if ($product->product_thumbs()->where('color_id', $color->color_id)->count() > 0) data-image="{{ $product->product_thumbs()->where('color_id', $color->color_id)->first()->img_path }}" @endif
                                                                @if ($product->product_colors()->count() == 1) @checked(true) @endif
                                                                hidden>
                                                            <div class="color-choosing-box">
                                                                <span>{{ $color->name }}</span>
                                                                <div class="color-mark"></div>
                                                            </div>
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            @error('product_color')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                                <div id="num-order-wp">
                                    <div class="d-flex num-order-container">
                                        <a title="" id="minus"><i class="fa fa-minus d-block"></i></a>
                                        <input type="number" class="no-spin-input-number input p-0" name="num_order"
                                            value="1" min="1" id="num-order">
                                        <a title="" id="plus"><i class="fa fa-plus d-block"></i></a>
                                    </div>
                                    @error('num_order')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <button type="submit" name="add_cart" title="Thêm giỏ hàng" class="view-btn"
                                    value="add_cart" height="36px">
                                    <i class="fa fa-shopping-cart"></i>
                                    Thêm giỏ hàng
                                </button>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="section" id="post-product-wp">
                    <div class="section-head">
                        <h3 class="section-title mt-0 bottom-lines-title">Chi tiết sản phẩm</h3>
                    </div>
                    <div class="section-detail">
                        {!! $product->detail !!}
                    </div>
                    <div class="section-head">
                        <h3 class="section-title mt-0 bottom-lines-title">Mô tả sản phẩm</h3>
                    </div>
                    <div class="section-detail">
                        {!! $product->desc !!}
                    </div>
                    <div class="show-more-btn">
                        <span><i class="fa-solid fa-angles-down"></i></span>
                    </div>
                </div>

                @if ($same_category_products->count() > 0)
                    <div class="section" id="same-category-wp">
                        <div class="d-flex justify-content-between position-relative section-head">
                            <!-- <h3 class="section-title">Sản phẩm nổi bật</h3> -->
                            <h3 class="m-0">Cùng danh mục</h3>
                            <div class="slider-nav-4">
                                <button type="button" class="slider-prev"><i class="fa-solid fa-angle-left"></i></button>
                                <button type="button" class="slider-next"><i class="fa-solid fa-angle-right"></i></button>
                            </div>
                        </div>
                        <div class="section-detail">
                            <div class="slider slider-4">

                                @foreach ($same_category_products as $item)
                                    @php
                                        if (!is_null($item->product_cat_name)) {
                                            $product_cat_slug = $item->product_cat_slug;
                                        } else {
                                            $product_cat_slug = 'san-pham-khac';
                                        }
                                    @endphp
                                    <div class="product-container">
                                        <div class="product-img">
                                            <a
                                                href="{{ route('product_detail', [
                                                    'product_category_slug' => $product_cat_slug,
                                                    'product_name' => $item->slug_convert($item->name),
                                                    'id' => $item->id,
                                                ]) }}">
                                                <img src="{{ $item->img_path }}" alt="">
                                            </a>
                                        </div>
                                        <div class="product-body">
                                            <div class="product-category">
                                                @if (!is_null($item->product_cat_name))
                                                    <p>{{ $item->product_cat_name }}</p>
                                                @else
                                                    <p>Khác</p>
                                                @endif
                                            </div>
                                            <div class="product-name">
                                                <a
                                                    href="{{ url('chi-tiet-san-pham/' . $item->slug_convert($item->name) . '.' . $item->id . '.html') }}">
                                                    {{ $item->name }}
                                                </a>
                                            </div>
                                            <h4 class="product-price">
                                                <span class="new">
                                                {{ number_format($item->price, 0, '') . 'đ' }}
                                                </span>
                                                {{-- <span class="old">
                                                    10.000.000
                                                </span> --}}
                                            </h4>
                                        </div>
                                        <div class="view-dropdown">
                                            <a href="{{ url('chi-tiet-san-pham/' . $item->slug_convert($item->name) . '.' . $item->id . '.html') }}"
                                                class="view-btn">
                                                <i class="fa-solid fa-eye"></i>
                                                Xem ngay
                                            </a>
                                        </div>

                                    </div>
                                @endforeach


                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <div class="sidebar fl-left">
                <div class="section" id="category-product-wp">
                    <div class="section-head mb-0">
                        <h3 class="section-title mb-0">Danh mục sản phẩm</h3>
                    </div>
                    <div class="secion-detail">
                        @if ($product_categories->count() > 0)
                            <ul class="list-item">
                                <li>
                                    <a href="{{ route('product') }}" title="">Tất cả</a>
                                </li>
                                @foreach ($product_categories->where('parent_id', 0) as $product_cat)
                                    <li>
                                        <a href="{{ route('product', $product_cat->slug) }}" title="">
                                            <span>{{ $product_cat->name }}</span>
                                        </a>
                                        @php
                                            $product_cat_children = $product_categories->where('parent_id', '=', $product_cat->id);
                                        @endphp
                                        @if ($product_cat_children->count() > 0)
                                            @include('client.layouts.subcategories', [
                                                'parent_id' => $product_cat->id,
                                            ])
                                        @endif
                                    </li>
                                @endforeach

                                <li>
                                    <a href="{{ route('product', 'san-pham-khac') }}" title="">Khác</a>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="section" id="selling-wp">
                    <div class="section-head mb-0">
                        <h3 class="section-title mb-0">Sản phẩm bán chạy</h3>
                    </div>
                    <div class="section-detail">
                        @if ($best_seller_products->count() > 0)
                            <ul class="list-item">
                                @foreach ($best_seller_products as $item)
                                    @php
                                        if (!is_null($item->product_cat_name)) {
                                            $product_cat_slug = $item->product_cat_slug;
                                        } else {
                                            $product_cat_slug = 'san-pham-khac';
                                        }
                                    @endphp
                                    <li class="clearfix">
                                        <div class="product-widget d-flex">
                                            <div class="product-img">
                                                <a
                                                    href="{{ route('product_detail', [
                                                        'product_category_slug' => $product_cat_slug,
                                                        'product_name' => $item->slug_convert($item->name),
                                                        'id' => $item->id,
                                                    ]) }}">
                                                    <img src="{{ $item->img_path }}" alt="">
                                                </a>
                                            </div>
                                            <div class="product-body">
                                                @if (!is_null($item->product_cat_name))
                                                    <p class="product-category">{{ $item->product_cat_name }}</p>
                                                @else
                                                    <p class="product-category">Khác</p>
                                                @endif
                                                <h3 class="product-name">
                                                    <a
                                                        href="{{ route('product_detail', [
                                                            'product_category_slug' => $product_cat_slug,
                                                            'product_name' => $item->slug_convert($item->name),
                                                            'id' => $item->id,
                                                        ]) }}">
                                                        {{ $item->name }}
                                                    </a>
                                                </h3>
                                                <h4 class="product-price">
                                                    {{ number_format($item->price, 0, '') . 'đ' }}
                                                    {{-- <del class="product-old-price">$990.00</del> --}}
                                                </h4>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
