@extends('layouts.admin')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm sản phẩm
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" action="{{ url('admin/product/store') }}" id="form-add">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Tên sản phẩm</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ old('name', request()->name) }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price">Giá</label>
                                <input class="form-control" min="0" type="number" name="price" id="price"
                                    value="{{ old('price', request()->price) }}">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Danh mục</label>
                                <select class="form-control" name="product_categories_selector" id="">
                                    <option value="0" disabled hidden>Chọn danh mục</option>
                                    <option value="0">Chưa xác định</option>
                                    @foreach ($product_cat_list as $product_cat)
                                        <option value="{{ $product_cat->id }}">
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
                                        <option value="{{ $product_brand->id }}">{{ $product_brand->name }}</option>
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
                                    </ul>
                                </div>
                                <hr>
                                <ul class="product-color-list d-flex flex-wrap">
                                    @foreach ($product_color_list as $color)
                                        <li>
                                            <div class="product-color">
                                                <span>{{ $color->name }}<input type="checkbox"
                                                        name="product_color_selector[]" id=""
                                                        value="{{ $color->id }}" hidden></span>
                                            </div>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                    </div>

                    <input type="file" name="product_thumb[]" id="product-thumb" data-total-files="0"
                        accept="image/png, image/jpg, image/jpeg" multiple hidden>
                    <label>Hình ảnh sản phẩm</label>
                    <div class="form-group border product-thumb-container">
                        <ul id="preview">
                            <li>
                                <div class="add-file-btn">
                                    <i class="fa-solid fa-plus d-block"></i>
                                </div>

                            </li>

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
                        <textarea name="detail" class="form-control ckeditor" id="detail" cols="30" rows="5">{{ old('detail', request()->detail) }}</textarea>
                        @error('detail')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="intro">Mô tả sản phẩm</label>
                        <textarea name="desc" class="form-control ckeditor" id="intro" cols="30" rows="12">{{ old('desc', request()->desc) }}</textarea>
                        @error('desc')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>



                    <div class="form-group">
                        <label for="">Trạng thái</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="product_status" id="private"
                                value="0" checked>
                            <label class="form-check-label" for="private">
                                Riêng tư
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="product_status" id="stocking"
                                value="1">
                            <label class="form-check-label" for="stocking">
                                Còn hàng
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="product_status" id="out-of-stock"
                                value="2">
                            <label class="form-check-label" for="out-of-stock">
                                Hết hàng
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" name="btn-add" id="btn-add">Thêm mới</button>


                </form>
            </div>
        </div>

    </div>

    <script src="{{ asset('js/jquery-3.6.0.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/add-product-thumbs.js') }}"></script>
@endsection
