@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách sản phẩm</h5>
                <div>
                    <form action="" class="form-search d-flex">
                        <input type="" class="form-control form-search mr-1" placeholder="Tìm kiếm" name="keyword"
                            value="{{ request()->input('keyword') }}">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{route('product_list')}}" class="text-primary">Tất cả<span class="text-muted">({{ $count[0] }})</span></a>
                    <a href="{{route('product_list',1)}}" class="text-primary">Còn hàng<span class="text-muted">({{ $count[3] }})</span></a>
                    <a href="{{route('product_list',2)}}" class="text-primary">Hết hàng<span class="text-muted">({{ $count[2] }})</span></a>
                    <a href="{{route('product_list',0)}}" class="text-primary">Riêng tư<span class="text-muted">({{ $count[4] }})</span></a>
                    <a href="{{route('product_list',3)}}" class="text-primary">Thùng rác<span class="text-muted">({{ $count[1] }})</span></a>
                </div>
                <form action="{{ url('admin/product/action') }}">
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="" name="act">
                            <option value="" hidden>Chọn</option>
                            @foreach ($list_act as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input name="checkall" type="checkbox">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Ảnh</th>
                                <th scope="col">Tên sản phẩm</th>
                                <th scope="col">Giá</th>
                                <th scope="col">Danh mục</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($products->count() > 0)
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($products as $item)
                                    @php
                                        $i++;
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="list_check[]" value="{{ $item->id }}">
                                        </td>
                                        <td>{{ $i }}</td>
                                        <td class="image120-container">
                                            <img src="{{ $product_thumbs->withTrashed()->where([['product_id', $item->id], ['order_id', 0]])->first('img_path')->img_path }}"
                                                alt="" class="img-contain">
                                        </td>
                                        <td><a href="{{ route('product_edit', $item->id) }}">{{ $item->name }}</a></td>
                                        <td>{{ number_format($item->price, 0, '') . 'đ'}}</td>
                                        @php
                                            $product_cat = $item->product_category()->first('name');
                                        @endphp
                                        @if ($product_cat != null)
                                            <td>{{ $product_cat->name }}</td>
                                        @else
                                            <td>Chưa xác định</td>
                                        @endif
                                        <td>{{ $item->updated_at }}</td>
                                        <td>
                                            @if ($item->status == 1)
                                                <span class="badge badge-success">Còn hàng</span>
                                            @endif
                                            @if ($item->status == 2)
                                                <span class="badge badge-warning">Hết hàng</span>
                                            @endif
                                            @if ($item->status == 0)
                                                <span class="badge badge-dark">Riêng tư</span>
                                            @endif

                                        </td>
                                        <td>
                                            @if ($status == 3)
                                                <a href="{{ route('product_restore', $item->id) }}"
                                                    class="btn btn-secondary btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Khôi phục">
                                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('product_edit', $item->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Chỉnh sửa">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endif


                                            <a href="{{ route('product_delete', $item->id) }}"
                                                class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Xoá"
                                                onclick="return confirm('{{ $warning }}')">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class=" bg-white">
                                        <p class="text-danger">Không tìm thấy kết quả</p>

                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                </form>

                {{ $products->links() }}
            </div>
        </div>
    </div>

@endsection
