@extends('client.layouts.product_default')
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
                            <li><a href="{{ url('/home') }}">Home</a></li>
                            <li><a href="{{ route('product') }}">Sản phẩm</a></li>
                            @if (!is_null($product_category_slug))
                                @if ($product_category_slug === 'san-pham-khac')
                                    <li class="active">
                                        Khác
                                    </li>
                                @else
                                    <li class="active">
                                        {{ $product_categories->where('slug', $product_category_slug)->first()->name }}
                                    </li>
                                @endif
                            @endif

                        </ul>
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /BREADCRUMB -->
    </div>
    <div id="main-content-wp" class="clearfix category-product-page">
        <div class="wp-inner">
            <div class="main-content fl-right">
                <div class="section" id="list-product-wp">
                    <div class="section">
                        <div class="d-flex justify-content-between section-head">
                            <!-- <h3 class="section-title">Sản phẩm nổi bật</h3> -->
                            <h3 class="m-0">Danh sách sản phẩm</h3>
                            <div class="show-result-count">
                                <span>Hiển thị {{ $product_list->count() }} trên
                                    {{ App\Models\Product::where('status', '<>', 0)->count() }} sản
                                    phẩm</span>
                            </div>
                        </div>
                        <div class="section-detail">
                            <div class="product-cards">
                                @if ($product_list->count() > 0)
                                    @foreach ($product_list as $item)
                                        @php
                                            if (!is_null($item->product_cat_name)) {
                                                $product_cat_slug = $item->product_cat_slug;
                                            } else {
                                                $product_cat_slug = 'san-pham-khac';
                                            }
                                        @endphp
                                        <div class="product-container">
                                            <div class="product-img position-relative">
                                                <a
                                                    href="{{ route('product_detail', [$product_cat_slug, $item->slug_convert($item->name), $item->id]) }}">
                                                    <img src="{{ $item->product_thumbs()->withTrashed()->where('order_id', 0)->first()->img_path }}"
                                                        alt="">
                                                </a>
                                                @if ($item->status == 2)
                                                    <div class="box-bg"></div>
                                                    <div class="out-of-stock-nortification">
                                                        <span>Hết</span>
                                                        <span>hàng</span>
                                                    </div>
                                                @endif
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
                                                        href="{{ route('product_detail', [$product_cat_slug, $item->slug_convert($item->name), $item->id]) }}">
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
                                                <a href="{{ route('product_detail', [$product_cat_slug, $item->slug_convert($item->name), $item->id]) }}"
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
                </div>
                <div class="fl-right section" id="paging-wp">
                    {{ $product_list->links() }}
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
                <div class="section" id="filter-product-wp">
                    <div class="section-head mb-0">
                        <h3 class="section-title mb-0">Bộ lọc</h3>
                    </div>
                    <div class="section-detail">
                        <table>
                            <thead>
                                <tr>
                                    <td colspan="2">
                                        <h3 class="selection-title">Giá</h3>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="input-radio">
                                            <input type="radio" value="1" name="price_filter" id="r_price_0"
                                                @if (request()->input('price_filter') == 1) checked @endif>
                                            <label for="r_price_0">
                                                <span></span>
                                                Dưới 500.000đ
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="input-radio">
                                            <input type="radio" value="2" name="price_filter" id="r_price_1"
                                                @if (request()->input('price_filter') == 2) checked @endif>
                                            <label for="r_price_1">
                                                <span></span>
                                                500.000đ - 1.000.000đ
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="input-radio">
                                            <input type="radio" value="3" name="price_filter" id="r_price_2"
                                                @if (request()->input('price_filter') == 3) checked @endif>
                                            <label for="r_price_2">
                                                <span></span>
                                                1.000.000đ - 5.000.000đ
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="input-radio">
                                            <input type="radio" value="4" name="price_filter" id="r_price_3"
                                                @if (request()->input('price_filter') == 4) checked @endif>
                                            <label for="r_price_3">
                                                <span></span>
                                                5.000.000đ - 10.000.000đ
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="input-radio">
                                            <input type="radio" value="5" name="price_filter" id="r_price_4"
                                                @if (request()->input('price_filter') == 5) checked @endif>
                                            <label for="r_price_4">
                                                <span></span>
                                                Trên 10.000.000đ
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table>
                            <thead>
                                <tr>
                                    <td colspan="2">
                                        <h3 class="selection-title">Hãng</h3>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($product_brands as $brand)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="input-checkbox">
                                                <input type="checkbox" id="brand-{{ $i }}" name="brand_filter[]"
                                                    value="{{ $brand->id }}"
                                                    @if (in_array($brand->id, $brand_filter)) @checked(true) @endif>
                                                <label for="brand-{{ $i }}">
                                                    <span></span>
                                                    {{ $brand->name }}
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table>
                            <thead>
                                <tr>
                                    <td colspan="2">
                                        <h3 class="selection-title">Sắp xếp theo</h3>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="input-radio">
                                            <input type="radio" value="1" name="orderby" id="r_orderby_0"
                                                @if (request()->orderby == 1) checked @endif>
                                            <label for="r_orderby_0">
                                                <span></span>
                                                Từ A-Z
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="input-radio">
                                            <input type="radio" value="2" name="orderby" id="r_orderby_1"
                                                @if (request()->orderby == 2) checked @endif>
                                            <label for="r_orderby_1">
                                                <span></span>
                                                Từ Z-A
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="input-radio">
                                            <input type="radio" value="3" name="orderby" id="r_orderby_2"
                                                @if (request()->orderby == 3) checked @endif>
                                            <label for="r_orderby_2">
                                                <span></span>
                                                Giá thấp đến cao
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="input-radio">
                                            <input type="radio" value="4" name="orderby" id="r_orderby_3"
                                                @if (request()->orderby == 4) checked @endif>
                                            <label for="r_orderby_3">
                                                <span></span>
                                                Giá cao xuống thấp
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="view-btn">
                            <i class="fa-solid fa-filter"></i>
                            Lọc
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
