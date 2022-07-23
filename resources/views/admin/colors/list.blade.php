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
                        Thêm màu
                    </div>
                    <div class="card-body">
                        <form action="{{ route('color-store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Tên màu</label>
                                <input class="form-control" type="text" name="name" id="name"
                                    value="{{ old('name', request()->name) }}">
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
                        Danh sách màu
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tên màu</th>
                                    <th scope="col">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($color_list->count() > 0)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($color_list as $color)
                                        @php
                                            $i++;
                                        @endphp
                                        <tr>
                                            <th scope="row">{{ $i }}</th>
                                            <td>{{ $color->name }}</td>
                                            <td>
                                                <a href="{{ route('color-delete', $color->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Xoá"
                                                    onclick="return confirm('Bạn có muốn xoá màu này?')">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class=" bg-white">
                                            <p class="text-danger">Không tìm thấy kết quả</p>

                                        </td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
