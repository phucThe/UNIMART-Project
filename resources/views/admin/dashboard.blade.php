@extends('layouts.admin')

@section('content')
    @if (session('message'))
        <div class="alert alert-danger">
            {{ session('message') }}
        </div>
    @endif
    <div class="container-fluid py-5">
        <div class="row dashboard-cards-wp">
            <div class="col">
                <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                    <div class="card-header">ĐƠN HÀNG THÀNH CÔNG</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $order_status_count['success'] }}</h5>
                        <p class="card-text">Đơn hàng giao dịch thành công</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                    <div class="card-header">ĐANG XỬ LÝ</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $order_status_count['processing'] }}</h5>
                        <p class="card-text">Số lượng đơn hàng đang xử lý</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                    <div class="card-header">DOANH SỐ</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ number_format($order_sales_total->sum_total, 0, '') . 'đ' }}</h5>
                        <p class="card-text">Doanh số hệ thống</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                    <div class="card-header">ĐƠN HÀNG HỦY</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $order_status_count['canceled'] }}</h5>
                        <p class="card-text">Số đơn bị hủy trong hệ thống</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- end analytic  -->
        <div class="card">
            <div class="card-header font-weight-bold">
                ĐƠN HÀNG MỚI
            </div>
            <div class="card-body">
                <table class="table table-striped table-checkall">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Mã</th>
                            <th scope="col">Khách hàng</th>
                            <th scope="col">Số điện thoại</th>
                            <th scope="col">Tổng tiền</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Thời gian</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if ($orders->count() > 0)
                            @php
                                $i = 0;
                            @endphp
                            @foreach ($orders as $order)
                                @php
                                    $i++;
                                @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $order->id }}</td>
                                    <td>
                                        {{ $order->fullname }}
                                    </td>
                                    <td>
                                        {{ $order->phone }}
                                    </td>
                                    <td>{{ number_format($order->total, 0, '') . 'đ' }}</td>
                                    <td>
                                        @if ($order->status == 2)
                                            <span class="badge badge-warning">Đang xử lý</span>
                                        @endif
                                        @if ($order->status == 3)
                                            <span class="badge badge-info">Đang vận chuyển</span>
                                        @endif
                                        @if ($order->status == 4)
                                            <span class="badge badge-success">Đã hoàn thành</span>
                                        @endif
                                        @if ($order->status == 0)
                                            <span class="badge badge-dark">Đã huỷ</span>
                                        @endif

                                    </td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>
                                        <a href="{{ route('order_detail', $order->id) }}"
                                            class="btn btn-primary btn-sm rounded-0 text-white" type="button"
                                            data-toggle="tooltip" data-placement="top" title="Chi tiết">
                                            <i class="fa-solid fa-ellipsis"></i>
                                        </a>
                                        <a href="{{ route('order_delete', $order->id) }}"
                                            class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                            data-toggle="tooltip" data-placement="top"
                                            onclick="return confirm('{{ $warning }}')" title="Xoá">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class=" bg-white">
                                    <p class="text-danger">Không tìm thấy kết quả</p>

                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
                {{ $orders->links() }}
            </div>
        </div>

    </div>

@endsection
