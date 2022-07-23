@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thêm danh mục
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/post/post-cat/store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên danh mục</label>
                                <input class="form-control" type="text" name="name" id="name" value="{{old('name', request()->name)}}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="slug">Slug đường dẫn</label>
                                <input class="form-control" type="text" name="slug" id="slug" value="{{old('slug', request()->slug)}}">
                                @error('slug')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Danh mục cha</label>
                                <select class="form-control" id="" name="parent_cat" value="{{old('parent_cat', request()->parent_cat)}}">
                                    <option value="0" hidden>Chọn danh mục</option>
                                    <option value="0">Không có</option>
                                    @foreach ($post_cat_list as $post_cat)
                                        <option value="{{ $post_cat->id }}">{{ str_repeat("|--- ", $post_cat->level).$post_cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('parent_cat')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Thêm mới</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Danh mục
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tên danh mục</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($post_cat_list as $post_cat)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <th scope="row">{{ $i }}</th>
                                        <td>{{ str_repeat("|--- ", $post_cat->level).$post_cat->name }}</td>
                                        <td>{{ $post_cat->slug }}</td>
                                        <td>
                                            @if ($post_cat->name != "Khác")
                                                <a href="{{ route('post_cat_edit', $post_cat->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Chỉnh sửa">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="{{ route('post_cat_delete', $post_cat->id)}}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Xoá"
                                                    onclick="return confirm('Bạn có muốn xoá danh mục này?')">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
