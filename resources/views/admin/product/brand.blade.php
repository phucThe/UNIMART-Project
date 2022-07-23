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
                        Thêm thương hiệu cho sản phẩm
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/product/product-brand/store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên thương hiệu</label>
                                <input class="form-control" type="text" name="name" id="name" value="{{old('name', request()->name)}}">
                                @error('name')
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
                        Danh sách thương hiệu
                    </div>
                    <div class="card-body">
                    <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tên thương hiệu</th>
                                    <th scope="col">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($product_brand_list as $product_brand)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <th scope="row">{{ $i }}</th>
                                        <td>{{ $product_brand->name }}</td>
                                        <td>
                                            <a href="{{ route('product-brand-delete', $product_brand->id)}}"
                                                class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Xoá"
                                                onclick="return confirm('Bạn có muốn xoá?')">
                                                <i class="fa fa-trash"></i>
                                            </a>
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
