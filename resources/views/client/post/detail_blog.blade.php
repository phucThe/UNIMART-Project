@extends('client.layouts.post_default')
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
                            <li><a href="{{ route('blog-list') }}">Bài viết</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /BREADCRUMB -->
    </div>
    <div id="main-content-wp" class="clearfix blog-page">
        <div class="wp-inner">
            <div class="blog-content fl-left">
                <div class="section" id="detail-blog-wp">
                    <div class="section-head clearfix">
                        <h3 class="section-title">{{ $post->title }}</h3>
                    </div>
                    <div class="section-detail">
                        <span class="create-date">{{ date('d-m-Y', strtotime($post->created_at)) }}</span>
                        <div class="detail">
                            {!! $post->content !!}
                        </div>
                    </div>
                </div>
                <!-- <div class="section" id="social-wp">
                                <div class="section-detail">
                                    <div class="fb-like" data-href="" data-layout="button_count" data-action="like" data-size="small" data-show-faces="true" data-share="true"></div>
                                    <div class="g-plusone-wp">
                                        <div class="g-plusone" data-size="medium"></div>
                                    </div>
                                    <div class="fb-comments" id="fb-comment" data-href="" data-numposts="5"></div>
                                </div>
                            </div> -->

                <div class="section" id="same-post-category-wp">
                    <div class="d-flex justify-content-between position-relative section-head">
                        <h3 class="m-0">Cùng chuyên mục</h3>
                        <div class="slider-nav-3">
                            <button type="button" class="slider-prev"><i class="fa-solid fa-angle-left"></i></button>
                            <button type="button" class="slider-next"><i class="fa-solid fa-angle-right"></i></button>
                        </div>
                    </div>
                    <div class="section-detail">
                        <div class="slider-3 post-cards">
                            @if ($related_posts->count() > 0)
                                @foreach ($related_posts as $post)
                                    <div class="post-container">
                                        <div class="post-img">
                                            <a
                                                href="{{route('blog-detail',[$post->slug_convert($post->title),$post->id])}}">
                                                <img src="{{ $post->thumb_path }}" alt="">
                                            </a>
                                            <div class="body">
                                                <h3>Cùng danh mục</h3>
                                                <a href="{{route('blog-detail',[$post->slug_convert($post->title),$post->id])}}"
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
                                                    href="{{route('blog-detail',[$post->slug_convert($post->title),$post->id])}}">
                                                    {{ $post->title }}
                                                </a>
                                            </div>
                                            <div class="post-desc">
                                                {!! $post->content !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif


                        </div>
                    </div>
                </div>

            </div>
            <div class="sidebar m-0 sidebar-post fl-right">
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
