@extends('layouts.admin')
@section('content')

<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm bài viết
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" action="{{ url('admin/post/store') }}">
                @csrf
                <div class="form-group">
                    <label for="title">Tiêu đề bài viết</label>
                    <input class="form-control" type="text" name="title" id="title" value="{{old('title', request()->title)}}">
                    @error('title')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="file" name="post_thumb" id="post-thumb" accept="image/png, image/jpg, image/jpeg" hidden>
                    <label>Ảnh bìa bài viết</label>
                    <div class="preview">
                        <div class="add-file-btn">
                            <i class="fa-solid fa-plus d-block"></i>
                        </div>
                    </div>

                    @error('post_thumb')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Danh mục đã được chọn</label>
                    <div class="selected-categories">
                        <ul class="d-flex flex-wrap">
                        </ul>
                    </div>
                    <hr>
                    <ul class="post-categories-list d-flex flex-wrap">
                        @foreach ($post_categories as $post_category)
                        <li>
                            <div class="post-category">
                                <span>{{ $post_category->name }}<input type="checkbox" name="categories_selector[]" id="" value="{{ $post_category->id }}" hidden></span>
                            </div>

                        </li>
                        @endforeach

                    </ul>
                </div>

                <div class="form-group">
                    <label for="content">Nội dung bài viết</label>
                    <textarea name="content" class="form-control ckeditor" id="content" cols="30" rows="5">{{old('content', request()->content)}}</textarea>
                    @error('content')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">Trạng thái</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="post_status" id="private" value="0" checked>
                        <label class="form-check-label" for="private">
                            Riêng tư
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="post_status" id="public" value="1">
                        <label class="form-check-label" for="public">
                            Công khai
                        </label>
                    </div>
                </div>



                <button type="submit" class="btn btn-primary">Thêm mới</button>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('js/jquery-3.6.0.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/add-post-thumb.js') }}" type="text/javascript"></script>
@endsection
