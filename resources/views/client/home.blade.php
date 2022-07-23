@extends('client.layouts.default')
@section('content')
    <div id="main-content-wp" class="home-page clearfix">
        <div class="wp-inner">
            <div class="main-content fl-right">
                <div class="section" id="slider-wp-1">
                    <div class="section-detail slider-1">
                        @foreach ($sliders as $slider)
                            @if (is_null($slider->link))
                                <div>
                                    <img src="{{ $slider->img_path }}" alt="">
                                </div>
                            @else
                                <a href="{{ $slider->link }}">
                                    <img src="{{ $slider->img_path }}" alt="">
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="section" id="support-wp">
                    <div class="section-detail">
                        <ul class="list-item clearfix">
                            <li>
                                <div class="icon">
                                    <i class="fa-solid fa-truck"></i>
                                </div>
                                <h3 class="title">Miễn phí vận chuyển</h3>
                                <p class="desc">Tới tận tay khách hàng</p>
                            </li>
                            <li>
                                <div class="icon">
                                    <i class="fa-solid fa-headphones-simple"></i>
                                </div>
                                <h3 class="title">Tư vấn 24/7</h3>
                                <p class="desc">1900.9999</p>
                            </li>
                            <li>
                                <div class="icon">
                                    <i class="fa-solid fa-piggy-bank"></i>
                                </div>
                                <h3 class="title">Tiết kiệm hơn</h3>
                                <p class="desc">Với nhiều ưu đãi cực lớn</p>
                            </li>
                            <li>
                                <div class="icon">
                                    <i class="fa-solid fa-coins"></i>
                                </div>
                                <h3 class="title">Thanh toán nhanh</h3>
                                <p class="desc">Hỗ trợ nhiều hình thức</p>
                            </li>
                            <li>
                                <div class="icon">
                                    <i class="fa-solid fa-cart-flatbed"></i>
                                </div>
                                <h3 class="title">Đặt hàng online</h3>
                                <p class="desc">Thao tác đơn giản</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="section" id="feature-product-wp">
                    <div class="d-flex justify-content-between position-relative section-head">
                        <h3 class="m-0">Sản phẩm nổi bật</h3>
                        <div class="slider-nav-4">
                            <button class="slider-prev"><i class="fa-solid fa-angle-left"></i></button>
                            <button class="slider-next"><i class="fa-solid fa-angle-right"></i></button>
                        </div>
                    </div>
                    <div class="section-detail">
                        <div class="slider slider-4">
                            @if ($feature_products->count() > 0)
                                @foreach ($feature_products as $item)
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
                                                <img src="{{ $item->product_thumbs()->withTrashed()->where('order_id', 0)->first()->img_path }}"
                                                    alt="">
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
                                                    href="{{ route('product_detail', [
                                                        'product_category_slug' => $product_cat_slug,
                                                        'product_name' => $item->slug_convert($item->name),
                                                        'id' => $item->id,
                                                    ]) }}">
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
                                            <a href="{{ route('product_detail', [
                                                'product_category_slug' => $product_cat_slug,
                                                'product_name' => $item->slug_convert($item->name),
                                                'id' => $item->id,
                                            ]) }}"
                                                class="view-btn">
                                                <i class="fa-solid fa-eye"></i>
                                                Xem ngay
                                            </a>
                                        </div>

                                    </div>
                                @endforeach
                            @endif

                        </div>
                    </div>
                </div>
                <div class="section" id="new-product-wp">
                    <div class="d-flex justify-content-between section-head">
                        <h3 class="m-0">Sản phẩm mới</h3>
                        <div class="section-tab-nav tabs-nav">
                            <ul class="d-flex">
                                <li class="active">
                                    <a href="#all">Tất cả</a>
                                </li>
                                @foreach ($new_products_categories as $product_cat)
                                    @if ($product_cat->products()->count() > 0)
                                        <li>
                                            <a href="#{{ $product_cat->slug }}">{{ $product_cat->name }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>

                        </div>
                    </div>
                    <div class="section-detail">
                        @foreach ($new_products as $key => $product_list)
                            @if ($product_list->count() > 0)
                                <div class="tab-content-item product-cards"
                                    @if ($key != 0) id="{{ $product_list->first()->product_cat_slug }}" @else id="all" @endif>
                                    @foreach ($product_list as $item)
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
                                                    <img src="{{ $item->product_thumbs()->withTrashed()->where('order_id', 0)->first()->img_path }}"
                                                        alt="">
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
                                                        href="{{ route('product_detail', [
                                                            'product_category_slug' => $product_cat_slug,
                                                            'product_name' => $item->slug_convert($item->name),
                                                            'id' => $item->id,
                                                        ]) }}">
                                                        {{ $item->name }}
                                                    </a>
                                                </div>
                                                <h4 class="product-price">
                                                    <span class="new">
                                                        {{ number_format($item->price, 0, '') . 'đ' }}
                                                    </span>
                                                    {{-- <span class="old">10.000.000</span> --}}
                                                </h4>
                                            </div>
                                            <div class="view-dropdown">
                                                <a href="{{ route('product_detail', [
                                                    'product_category_slug' => $product_cat_slug,
                                                    'product_name' => $item->slug_convert($item->name),
                                                    'id' => $item->id,
                                                ]) }}"
                                                    class="view-btn">
                                                    <i class="fa-solid fa-eye"></i>
                                                    Xem ngay
                                                </a>
                                            </div>

                                        </div>
                                    @endforeach



                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="section" id="list-new-post-wp">
                    <div class="d-flex justify-content-between position-relative section-head">
                        <!-- <h3 class="section-title">Sản phẩm nổi bật</h3> -->
                        <h3 class="m-0">Bài viết mới</h3>
                        <div class="slider-nav-3">
                            <button class="slider-prev"><i class="fa-solid fa-angle-left"></i></button>
                            <button class="slider-next"><i class="fa-solid fa-angle-right"></i></button>
                        </div>
                    </div>
                    <div class="section-detail">
                        <div class="slider-3 post-cards">
                            @foreach ($new_posts as $post)
                                <div class="post-container">
                                    <div class="post-img">
                                        <a
                                            href="{{ route('blog-detail', [$post->slug_convert($post->title), $post->id]) }}">
                                            <img src="{{ $post->thumb_path }}" alt="">
                                        </a>
                                        <div class="body">
                                            <h3>Mới nhất</h3>
                                            <a href="{{ route('blog-detail', [$post->slug_convert($post->title), $post->id]) }}"
                                                class="text-uppercase">Xem ngay <i
                                                    class="fa-solid fa-circle-arrow-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="post-info">
                                        <div class="post-date">
                                            <p>{{ date('d-m-Y', strtotime($post->created_at)) }}</p>
                                        </div>
                                        <div class="post-title">
                                            <a
                                                href="{{ route('blog-detail', [$post->slug_convert($post->title), $post->id]) }}">
                                                {{ $post->title }}
                                            </a>
                                        </div>
                                        <div class="post-desc">
                                            {!! $post->content !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
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
