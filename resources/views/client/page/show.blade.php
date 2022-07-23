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
                        <li class="active">{{$page->title}}</li>
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
                        <h3 class="section-title">{{ $page->title }}</h3>
                    </div>
                    <div class="section-detail">
                        <span class="create-date">{{ date('d-m-Y', strtotime($page->created_at)) }}</span>
                        <div class="detail">
                            {!! $page->content !!}
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
