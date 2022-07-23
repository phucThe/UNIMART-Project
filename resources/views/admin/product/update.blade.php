@extends('layouts.admin')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thông tin sản phẩm
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('admin/product/update', $id) }}">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Tên sản phẩm</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ $product->name }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price">Giá</label>
                                <input class="form-control" type="number" name="price" id="price"
                                    value="{{ $product->price }}">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Danh mục</label>
                                <select class="form-control" name="product_categories_selector" id="">
                                    <option value="0" disabled hidden>Chọn danh mục</option>
                                    @if ($product->product_cat_id == null)
                                        <option value="0">Chưa xác định</option>
                                    @endif
                                    @foreach ($product_cat_list as $product_cat)
                                        <option @if ($product_cat->id == $product->product_cat_id) @selected(true) @endif
                                            value="{{ $product_cat->id }}">
                                            {{ str_repeat('|--- ', $product_cat->level) . $product_cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_categories_selector')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Thương hiệu</label>
                                <select class="form-control" name="product_brands_selector" id="">
                                    <option value="0" disabled hidden>Chọn thương hiệu</option>
                                    <option value="0">Không có thương hiệu</option>
                                    @foreach ($product_brand_list as $product_brand)
                                        <option value="{{ $product_brand->id }}"
                                            @if ($product_brand->id == $product->brand_id) selected @endif>{{ $product_brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_brands_selector')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Chọn màu sản phẩm</label>
                                <div class="selected-colors">
                                    <ul class="d-flex flex-wrap">
                                        @php
                                            $product_color_id_list = [];
                                        @endphp
                                        @foreach ($product_color_list as $product_color)
                                            <li>
                                                <div class="product-color product-color-active"
                                                    data-id="{{ $product_color->id }}">
                                                    <span><i
                                                            class="fa-solid fa-xmark"></i>{{ ' ' . $product_color->name }}</span>
                                                </div>
                                            </li>
                                            @php
                                                $product_color_id_list[] = $product_color->id;
                                            @endphp
                                        @endforeach
                                    </ul>
                                </div>
                                <hr>
                                <ul class="product-color-list d-flex flex-wrap">
                                    @foreach ($colors as $color)
                                        <li>
                                            @if (in_array($color->id, $product_color_id_list))
                                                <div class="product-color product-color-active">
                                                    <span>{{ $color->name }}<input type="checkbox"
                                                            name="product_color_selector[]" id=""
                                                            value="{{ $color->id }}" hidden checked></span>
                                                </div>
                                            @else
                                                <div class="product-color">
                                                    <span>{{ $color->name }}<input type="checkbox"
                                                            name="product_color_selector[]" id=""
                                                            value="{{ $color->id }}" hidden></span>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                    </div>

                    <label>Hình ảnh sản phẩm</label>
                    <input type="file" name="product_thumb[]" id="product-thumb"
                        data-total-files="{{ $product_img['product_img_count'] }}"
                        accept="image/png, image/jpg, image/jpeg" multiple hidden>
                    <div class="form-group border product-thumb-container">
                        <ul id="preview">
                            <li>
                                <div class="add-file-btn">
                                    <i class="fa-solid fa-plus d-block"></i>
                                </div>

                            </li>

                            @php
                                $i = 0;
                            @endphp
                            @foreach ($product_img['product_img_list'] as $img)
                                <li>

                                    <div class="img-box">
                                        <img src="{{ $img->img_path }}">

                                        <div class="black-bg">
                                            <div class="remove-img-btn" data-id="{{ $img->id }}">
                                                <span>
                                                    <i class="fa-solid fa-xmark"></i>
                                                </span>

                                            </div>

                                        </div>
                                        <div class="thumb-role-txt @if ($i == 0) main-thumb @endif">
                                            @if ($i == 0)
                                                <span>Ảnh bìa</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <select name="order_id[]" data-id="{{ $i }}"
                                            class="order-id form-control">
                                            <option value="{{ $product_img['product_img_count'] }}" hidden>#
                                            </option>
                                            @for ($k = 0; $k < $product_img['product_img_count']; $k++)
                                                @if ($k == 0)
                                                    <option value="0" selected>0</option>
                                                @else
                                                    <option value="{{ $k }}"
                                                        @if ($k == $i) selected @endif>
                                                        {{ $k }}
                                                    </option>
                                                @endif
                                            @endfor
                                        </select>

                                        <select name="color_selector[]" data-id="{{ $i }}"
                                            class="color-id form-control">
                                            <option value="0" hidden selected>Chọn màu</option>
                                            <option value="0">Không có</option>
                                            @foreach ($colors as $color)
                                                <option value="{{ $color->id }}"
                                                    @if ($img->color_id == $color->id) selected @endif>
                                                    {{ $color->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </li>
                                @php
                                    $i++;
                                @endphp
                            @endforeach


                        </ul>
                    </div>
                    @error('product_thumb.*')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    @error('product_thumb')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    <div class="form-group">
                        <label for="detail">Chi tiết sản phẩm</label>
                        <textarea name="detail" class="form-control ckeditor" id="detail" cols="30" rows="5">{{ $product->detail }}</textarea>
                        @error('detail')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="intro">Mô tả sản phẩm</label>
                        <textarea name="desc" class="form-control ckeditor" id="intro" cols="30" rows="12">{{ $product->desc }}</textarea>
                        @error('desc')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">Trạng thái</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="product_status" id="private"
                                value="0" @if ($product->status == 0) @checked(true) @endif>
                            <label class="form-check-label" for="private">
                                Riêng tư
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="product_status" id="stocking"
                                value="1" @if ($product->status == 1) @checked(true) @endif>
                            <label class="form-check-label" for="stocking">
                                Còn hàng
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="product_status" id="out-of-stock"
                                value="2" @if ($product->status == 2) @checked(true) @endif>
                            <label class="form-check-label" for="out-of-stock">
                                Hết hàng
                            </label>
                        </div>
                    </div>



                    <button type="submit" class="btn btn-primary" name='btn-add'>Cập nhật</button>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery-3.6.0.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/add-product-thumbs.js') }}"></script>
@endsection
