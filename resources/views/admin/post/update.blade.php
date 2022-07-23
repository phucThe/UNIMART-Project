@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thông tin bài viết
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('admin/post/update', $post->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="title">Tiêu đề bài viết</label>
                    <input class="form-control" type="text" name="title" id="title" value="{{ $post->title }}">
                    @error('title')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="file" name="post_thumb" id="post-thumb" accept="image/png, image/jpg, image/jpeg" hidden>
                    <label>Ảnh bìa bài viết</label>
                    <div class="preview">
                        <div class="post-thumb-container">
                            <div class="black-bg">
                                <div class="change-img-btn">
                                    <span>
                                        <i class="fa-solid fa-pen"></i>
                                    </span>

                                </div>

                            </div>
                            <img src="{{ $post->thumb_path }}" alt="">
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
                            @php
                            $post_post_categories_id_list = [];
                            @endphp
                            @foreach ($post_post_categories as $post_post_category)
                            <li>
                                <div class="post-category-active" data-id="{{ $post_post_category->id }}">
                                    <span><i class="fa-solid fa-xmark"></i>{{" ".$post_post_category->name }}</span>
                                </div>
                            </li>
                            @php
                            $post_post_categories_id_list[] = $post_post_category->id;
                            @endphp
                            @endforeach

                        </ul>
                    </div>
                    <hr>
                    <ul class="post-categories-list d-flex flex-wrap">
                        @foreach ($post_categories as $post_category)
                        <li>
                            @if (in_array($post_category->id, $post_post_categories_id_list))
                            <div class="post-category post-category-active">
                                <span>{{ $post_category->name }}<input checked type="checkbox" name="categories_selector[]" id="" value="{{ $post_category->id }}" hidden></span>
                            </div>
                            @else
                            <div class="post-category">
                                <span>{{ $post_category->name }}<input type="checkbox" name="categories_selector[]" id="" value="{{ $post_category->id }}" hidden></span>
                            </div>
                            @endif

                        </li>
                        @endforeach


                    </ul>
                    @error('categories_selector')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content">Nội dung bài viết</label>
                    <textarea name="content" class="form-control ckeditor" id="content" cols="30" rows="5">{{ $post->content }}</textarea>
                    @error('content')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">Trạng thái</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="post_status" id="private" value="0" @if ($post->status == 0) @checked(true) @endif>
                        <label class="form-check-label" for="private">
                            Riêng tư
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="post_status" id="public" value="1" @if ($post->status == 1) @checked(true) @endif>
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
<script src="{{ asset('js/add-post-thumb.js') }}" type="text/javascript"></script>
@endsection
