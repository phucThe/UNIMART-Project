@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thông tin trang
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('admin/page/update', $id) }}">
                @csrf
                <div class="form-group">
                    <label for="title">Tiêu đề trang</label>
                    <input class="form-control" type="text" name="title" id="title" value="{{$page->title}}">
                    @error('title')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="content">Nội dung trang</label>
                    <textarea name="content" class="form-control ckeditor" id="content" cols="30" rows="5">{{$page->content}}</textarea>
                    @error('content')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">Trạng thái</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="page_status" id="private" value="0" @if ($page->status == 0) @checked(true) @endif>
                        <label class="form-check-label" for="private">
                            Riêng tư
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="page_status" id="public" value="1" @if ($page->status == 1) @checked(true) @endif>
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
</div>
@endsection
