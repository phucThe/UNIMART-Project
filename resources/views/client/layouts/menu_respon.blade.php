<div id="menu-responsive" class="">
    <div class="menu-top">
        <div class="logo">
            <a href="?page=home" title="">Unimart</a>
        </div>
        <button class="respon-btn">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="menu-body">
        <ul class="menu-list">
            <li>
                <a href="{{ url('/home') }}" title="">Trang chủ</a>
            </li>

            <!-- Sản phẩm  -->
            <li>
                <a href="{{ url('/home') }}" title="">Sản phẩm</a>
                @if ($product_categories->count() > 0)
                    <button class="responsive-menu-toggle">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <ul class="sub-menu">
                        <li>
                            <a href="{{ route('product') }}" title="">Tất cả</a>
                        </li>
                        @foreach ($product_categories->where('parent_id',0) as $product_cat)
                            <li>
                                <a href="{{ route('product', $product_cat->slug) }}" title>{{ $product_cat->name }}</a>
                                @php
                                    $product_cat_children = $product_categories->where('parent_id', '=', $product_cat->id);
                                @endphp
                                @if ($product_cat_children->count() > 0)
                                    <button class="responsive-menu-toggle">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                    @include('client.layouts.menu_respon_subcategories', [
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
            </li>
            <li>
                <a href="{{ route('blog-list') }}" title="">Blog</a>
            </li>
            @if ($pages->count() > 0)
                @foreach ($pages as $page)
                    <li>
                        <a href="" title>{{ $page->title }}</a>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>
