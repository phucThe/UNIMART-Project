<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipping;
use App\Models\Order;
use App\Models\OrderDetail;

class CheckOutController extends Controller
{
    function show()
    {
        return view('client.checkout');
    }

    function verify_order_messeage($order_id)
    {

        $order_info = Order::join('shipping', 'orders.id', '=', 'shipping.order_id')
            ->select('orders.id', 'orders.total', 'orders.status', 'orders.updated_at', 'shipping.fullname', 'shipping.email', 'shipping.address', 'shipping.phone', 'shipping.note')
            ->where('orders.id', $order_id)
            ->first();
        $order_products = OrderDetail::join('products', 'products.id', '=', 'order_details.product_id')
            ->leftJoin('product_cats', 'products.product_cat_id', '=', 'product_cats.id')
            ->join('product_thumbs', 'products.id', '=', 'product_thumbs.product_id')
            ->select('order_details.*', 'products.name as productName', 'product_cats.slug as productCatSlug', 'products.price', 'product_cats.name as productCatName', 'product_thumbs.img_path')
            ->where([
                ['order_details.order_id', $order_id],
                ['product_thumbs.order_id', 0],
            ])
            ->orderBy('products.name')
            ->get();

        return view('client.verify-order', compact('order_info', 'order_products'));
    }
}
