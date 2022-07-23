@php
$sub_category = $product_categories->where('parent_id', '=', $parent_id);
@endphp
@if ($sub_category->count() > 0)
    <button class="responsive-menu-toggle">
        <i class="fa-solid fa-plus"></i>
    </button>
    <ul class="sub-menu">
        @foreach ($sub_category as $category)
            <li>
                <a href="{{ route('product', $category->slug) }}" title="">
                    <span>{{ $category->name }}</span>
                </a>

                @include('client.layouts.menu_respon_subcategories', ['parent_id' => $category->id])
            </li>
        @endforeach
    </ul>
@endif
