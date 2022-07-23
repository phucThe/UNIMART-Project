@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thông tin khách hàng
            </div>
            <div class="card-body">
                <table class="table table-striped table-checkall">
                    <tr>
                        <td>Mã đơn hàng:</td>
                        <td colspan="2">{{$shipping_info->id}}</td>
                    </tr>
                    <tr>
                        <td>Tên khách hàng:</td>
                        <td colspan="2">{{$shipping_info->fullname}}</td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td colspan="2">{{$shipping_info->email}}</td>
                    </tr>
                    <tr>
                        <td>Địa chỉ:</td>
                        <td colspan="2">{{$shipping_info->address}}</td>
                    </tr>
                    <tr>
                        <td>Số điện thoại:</td>
                        <td colspan="2">{{$shipping_info->phone}}</td>
                    </tr>
                    <tr>
                        <td>Ngày tạo:</td>
                        <td colspan="2">{{$shipping_info->updated_at}}</td>
                    </tr>
                    <tr>
                        <td>Ghi chú:</td>
                        <td colspan="2">{{$shipping_info->note}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <h6 class="text-uppercase font-weight-bold">Tổng tiền:</h6>
                        </td>
                        <td>
                            <h6 class="text-uppercase font-weight-bold">{{number_format($shipping_info->total, 0, '') . ' đ'}}</h6>
                        </td>
                    </tr>
                </table>
                <form class="form-inline" action="{{route('order_update', $shipping_info->id)}}" method="POST">
                    @csrf
                    <div class="form-group mb-2">
                      <label>Cập nhật trạng thái</label>
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                        <select class="form-control" name="order_status">
                            <option value="2" @if ($shipping_info->status == 2)
                                @selected(true)
                            @endif>Đang xử lý</option>
                            <option value="3" @if ($shipping_info->status == 3)
                                @selected(true)
                            @endif>Đang vận chuyển</option>
                            <option value="4" @if ($shipping_info->status == 4)
                                @selected(true)
                            @endif>Hoàn thành</option>
                            <option value="0" @if ($shipping_info->status == 0)
                                @selected(true)
                            @endif>Đã huỷ</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2" value="status_update" name="status_update_btn">Cập nhật</button>
                  </form>
            </div>
        </div>
        <br>

        <div class="card">
            <div class="card-header font-weight-bold">
                Chi tiết đơn hàng
            </div>
            <div class="card-body">
                <table class="table table-striped table-checkall">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Giá</th>
                            <th scope="col">Danh mục</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Màu</th>
                            <th scope="col">Tổng</th>

                        </tr>
                    </thead>
                    <tbody>
                        @if ($order_detail->count() > 0)
                            @php
                                $i = 0;
                            @endphp
                            @foreach ($order_detail as $item)
                                @php
                                    $i++;
                                @endphp
                                <tr>

                                    <td>{{ $i }}</td>
                                    <td>
                                        <img src="{{ $item->img_path }}" alt="" height="auto" width="80px">
                                    </td>
                                    <td><a href="">{{ $item->productName }}</a></td>
                                    <td>{{ number_format($item->price, 0, '') . 'đ' }}</td>
                                    @if ($item->productCatName == '')
                                        <td>Không có</td>
                                    @else
                                        <td>{{ $item->productCatName }}</td>
                                    @endif
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ $item->product_color }}</td>
                                    <td>{{ number_format($item->sub_total, 0, '') . 'đ' }}</td>
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
            </div>
        </div>
    </div>
@endsection
