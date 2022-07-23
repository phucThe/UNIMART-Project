@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thông tin Slider
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" action="{{ url('admin/slider/update', $slider->id) }}">
                @csrf
                <div class="form-group">
                    <label for="slider-name">Tên Slider</label>
                    <input class="form-control" type="text" name="slider_name" id="slider-name" value="{{$slider->name}}">
                    @error('slider_name')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="slider-link">Tên Slider</label>
                    <input class="form-control" type="text" name="slider_link" id="slider-link" value="{{$slider->link}}">
                    @error('slider_link')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="slider-desc">Mô tả slider</label>
                    <textarea name="slider_desc" class="form-control" id="slider-desc" cols="30" rows="5">{{$slider->desc}}</textarea>
                    @error('slider_desc')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="file" name="slider_image" id="slider-image" accept="image/png, image/jpg, image/jpeg" hidden>
                    <label>Ảnh slider</label>
                    <div class="preview">
                    <div class="slider-thumb-container">
                            <div class="black-bg">
                                <div class="change-img-btn">
                                    <span>
                                        <i class="fa-solid fa-pen"></i>
                                    </span>

                                </div>

                            </div>
                            <img src="{{$slider->img_path}}" alt="">
                        </div>
                    </div>

                    @error('slider_image')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">Trạng thái</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="slider_status" id="private" value="0" @if ($slider->status == 0) @checked(true) @endif>
                        <label class="form-check-label" for="private">
                            Riêng tư
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="slider_status" id="public" value="1" @if ($slider->status == 1) @checked(true) @endif>
                        <label class="form-check-label" for="public">
                            Công khai
                        </label>
                    </div>
                </div>



                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('js/jquery-3.6.0.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/add-slider-thumb.js') }}" type="text/javascript"></script>
@endsection
