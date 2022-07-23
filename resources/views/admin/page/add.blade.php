@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm trang
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('admin/page/store') }}">
                @csrf
                <div class="form-group">
                    <label for="title">Tiêu đề trang</label>
                    <input class="form-control" type="text" name="title" id="title" value="{{old('title', request()->title)}}">
                    @error('title')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="content">Nội dung trang</label>
                    <textarea name="content" class="form-control ckeditor" id="content" cols="30" rows="5">{{old('content', request()->content)}}</textarea>
                    @error('content')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">Trạng thái</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="page_status" id="private" value="0" checked>
                        <label class="form-check-label" for="private">
                            Riêng tư
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="page_status" id="public" value="1">
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
</div>
@endsection
