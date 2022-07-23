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
                <div class="section" id="list-blog-wp">
                    <div class="section-head clearfix">
                        <h3 class="section-title">Bài viết</h3>
                    </div>
                    <div class="section-detail">
                        <ul class="list-item">
                            @if ($posts->count() > 0)
                                @foreach ($posts as $post)
                                    <li class="clearfix">
                                        <a href="{{route('blog-detail',[$post->slug_convert($post->title),$post->id])}}"
                                            title="" class="thumb fl-left">
                                            <img src="{{ $post->thumb_path }}" alt="">

                                        </a>
                                        <div class="post-info fl-right">
                                            <a
                                                href="{{route('blog-detail',[$post->slug_convert($post->title),$post->id])}}">
                                                <h3 class="title">{{ $post->title }}</h3>
                                            </a>
                                            <span
                                                class="create-date">{{ date('d-m-Y', strtotime($post->created_at)) }}</span>
                                            <div class="desc">
                                                {!! $post->content !!}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            @endif


                        </ul>
                    </div>
                </div>
                <div class="section" id="paging-wp">
                    {{ $posts->links() }}
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
