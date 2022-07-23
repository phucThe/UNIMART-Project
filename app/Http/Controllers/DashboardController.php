<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    //
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'dashboard']);

            return $next($request);
        });
    }

    function show()
    {
        $warning = "Đơn hàng sẽ được chuyển vào thùng rác?";

        $orders = Order::join('shipping', 'orders.id', '=', 'shipping.order_id')
            ->select('orders.*', 'shipping.fullname', 'shipping.phone')
            ->where([
                ['orders.is_active', '1'],
            ])
            ->orderBy('orders.updated_at', 'desc')
            ->paginate(10);

        $order_status_count = [];

        $order_canceled_status = 0;
        $order_processing_status = 2;
        $order_success_status = 4;

        $order_status_count['success'] = Order::where('status','=',$order_success_status)->count();
        $order_status_count['processing'] = Order::where('status','=',$order_processing_status)->count();
        $order_status_count['canceled'] = Order::where('status','=',$order_canceled_status)->count();

        $order_sales_total = Order::where('status','=', $order_success_status)->selectRaw('sum(orders.total) as sum_total')->first();


        return view('admin.dashboard', compact('orders', 'warning', 'order_status_count','order_sales_total'));
    }
}
