@php
$sub_category = $product_categories->where('parent_id', '=', $parent_id);
@endphp
@if ($sub_category->count() > 0)
    <ul class="sub-menu">
        @foreach ($sub_category as $category)
            <li>
                <a href="{{route('product',$category->slug)}}" title="">
                    <span>{{ $category->name }}</span>
                </a>
                @include('client.layouts.subcategories',['parent_id' => $category->id])
            </li>
        @endforeach
    </ul>
@endif
