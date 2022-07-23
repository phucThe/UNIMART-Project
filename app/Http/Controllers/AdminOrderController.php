<?php

namespace App\Http\Controllers;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use App\Models\Shipping;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use App\Mail\OrderDetailMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class AdminOrderController extends Controller
{

    private $canceled_status = 0;
    private $not_active_status = 1;
    private $processing_status = 2;
    private $being_transported_status = 3;
    private $success_status = 4;
    private $trash = 5;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'order']);

            return $next($request);
        });

        // Thiết lập session để add class active vào bên thanh sidebar
    }

    function list(Request $request, $status = null)
    {
        $keyword = '';
        $warning = "";
        $list_act = ['delete' => 'Chuyển vào thùng rác'];

        if ($request->has('keyword')) $keyword = $request->keyword;

        $orders = Order::join('shipping', 'orders.id', '=', 'shipping.order_id')
            ->select('orders.*', 'shipping.fullname', 'shipping.phone')
            ->where([
                ['orders.is_active', '1'],
                // ['orders.status', '=', $status],
                ['shipping.fullname', 'LIKE', "%{$keyword}%"]
            ])
            ->orWhere([
                ['orders.is_active', '1'],
                // ['orders.status', '=', $status],
                ['shipping.phone', 'LIKE', "%{$keyword}%"],
            ])
            ->paginate(10)->appends('keyword',$keyword);
        $warning = "Đơn hàng sẽ được chuyển vào thùng rác?";

        if (!is_null($status)) {
            if ($status != $this->trash) {
                $orders = Order::join('shipping', 'orders.id', '=', 'shipping.order_id')
                    ->select('orders.*', 'shipping.fullname', 'shipping.phone')
                    ->where([
                        ['orders.is_active', '1'],
                        ['orders.status', '=', $status],
                        ['shipping.fullname', 'LIKE', "%{$keyword}%"]
                    ])
                    ->orWhere([
                        ['orders.is_active', '1'],
                        ['orders.status', '=', $status],
                        ['shipping.phone', 'LIKE', "%{$keyword}%"],
                    ])
                    ->paginate(10)->appends('keyword',$keyword);
            } else {
                $orders = Order::onlyTrashed()
                    ->join('shipping', 'orders.id', '=', 'shipping.order_id')
                    ->select('orders.*','shipping.id', 'shipping.fullname', 'shipping.phone')
                    ->where([
                        ['orders.is_active', '1'],
                        ['orders.deleted_at', '<>', null],
                        ['shipping.fullname', 'LIKE', "%{$keyword}%"]
                    ])
                    ->orWhere([
                        ['orders.is_active', '1'],
                        ['orders.deleted_at', '<>', null],
                        ['shipping.phone', 'LIKE', "%{$keyword}%"],
                    ])
                    ->paginate(10)->appends('keyword',$keyword);

                $warning = "Bạn có muốn xoá vĩnh viễn đơn hàng này?";
                $list_act = [
                    'forceDelete' => 'Xoá vĩnh viễn',
                    'restore' => 'Khôi phục',
                ];
            }
        }

        $count_orders_all = Order::count();
        $count_orders_canceled = Order::where('status', $this->canceled_status)->count();
        $count_orders_processing = Order::where('status', $this->processing_status)->count();
        $count_orders_being_transported = Order::where('status', $this->being_transported_status)->count();
        $count_orders_success = Order::where('status', $this->success_status)->count();
        $count_orders_trash = Order::onlyTrashed()->count();

        $count = [
            $count_orders_all, //0
            $count_orders_canceled, //1
            $count_orders_processing, //2
            $count_orders_being_transported, //3
            $count_orders_success, //4
            $count_orders_trash, //5
        ];
        return view('admin.order.list', compact('orders', 'list_act', 'warning', 'count', 'status'));
    }

    function store(Request $request)
    {
        $request->validate(
            [
                'fullname' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9_\. aAàÀảẢãÃáÁạẠăĂằẰẳẲẵẴắẮặẶâÂầẦẩẨẫẪấẤậẬbBcCdDđĐeEèÈẻẺẽẼéÉẹẸêÊềỀểỂễỄếẾệỆfFgGhHiIìÌỉỈĩĨíÍịỊjJkKlLmMnNoOòÒỏỎõÕóÓọỌôÔồỒổỔỗỖốỐộỘơƠờỜởỞỡỠớỚợỢpPqQrRsStTuUùÙủỦũŨúÚụỤưƯừỪửỬữỮứỨựỰvVwWxXyYỳỲỷỶỹỸýÝỵỴzZ]{6,32}$/'],
                'email' => ['required', 'string', 'email', 'max:255', 'regex:/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/'],
                'phone' => ['required', 'regex:/^[0-9]{8,11}$/'],
                'address' => ['required', 'regex:/[A-Za-z0-9_\. aAàÀảẢãÃáÁạẠăĂằẰẳẲẵẴắẮặẶâÂầẦẩẨẫẪấẤậẬbBcCdDđĐeEèÈẻẺẽẼéÉẹẸêÊềỀểỂễỄếẾệỆfFgGhHiIìÌỉỈĩĨíÍịỊjJkKlLmMnNoOòÒỏỎõÕóÓọỌôÔồỒổỔỗỖốỐộỘơƠờỜởỞỡỠớỚợỢpPqQrRsStTuUùÙủỦũŨúÚụỤưƯừỪửỬữỮứỨựỰvVwWxXyYỳỲỷỶỹỸýÝỵỴzZ]+/'],

            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có độ dài ít nhất :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'regex' => ":attribute không đúng định dạng",
                'email.unique' => "Đã tồn tại email này trong hệ thống",
            ],
            [
                'fullname' => 'Tên người dùng',
                'email' => 'Email',
                'phone' => 'Số điện thoại',
                'address' => "Địa chỉ",
                'note' => "Ghi chú"
            ]
        );

        // Huỷ đơn hàng không được xác nhận trong 1 giờ
        $unverified_orders = Order::where('is_active', '0')->get();
        if ($unverified_orders->count() > 0) {
            foreach ($unverified_orders as $order) {
                $active_time = strtotime($order->first()->created_at);
                if (time() - $active_time >= 3600) {
                    $order->update([
                        'is_active' => '1',
                        'status' => $this->canceled_status,
                    ]);
                }
            }
        }

        $order = Order::create([
            'total' => (int)Cart::subtotal(0, "", ""),
            'status' => $this->not_active_status,
            'is_active' => '0',
            'active_token' => Hash::make($request->fullname . time()),
        ]);

        $shipping = Shipping::create([
            'order_id' => $order->id,
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'note' => $request->note,
        ]);

        // Lưu lại thông tin khách hàng vào cookies
        $minutes = 129600; // 3 tháng
        Cookie::queue('customer_fullname', $shipping->fullname, $minutes);
        Cookie::queue('customer_email', $shipping->email, $minutes);
        Cookie::queue('customer_phone', $shipping->phone, $minutes);
        Cookie::queue('customer_address', $shipping->address, $minutes);

        $product_list = Cart::content();
        if ($product_list->count() > 0) {
            foreach ($product_list as $product) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_color' => $product->options->color,
                    'qty' => $product->qty,
                    'sub_total' => $product->total,
                ]);
            }
        }

        // $shipping_info = Order::join('shipping','orders.id','=','shipping.order_id')
        // ->select('orders.id','orders.total','orders.status','orders.updated_at','shipping.fullname','shipping.email','shipping.address','shipping.phone','shipping.note')
        // ->where('orders.id',$order->id)
        // ->first();
        // $order_products = OrderDetail::join('products','products.id','=','order_details.product_id')
        // ->leftJoin('product_cats','products.product_cat_id','=','product_cats.id')
        // ->join('product_thumbs','products.id','=','product_thumbs.product_id')
        // ->select('order_details.*','products.name as productName','product_cats.slug as productCatSlug','products.price','product_cats.name as productCatName','product_thumbs.img_path')
        // ->where([
        //     ['order_details.order_id',$shipping->id],
        //     ['product_thumbs.order_id',0],
        // ])
        // ->orderBy('products.name')
        // ->get();

        Cart::destroy();

        return redirect(route('checkout_message',$order->id));

    }

    function confirm(Request $request)
    {
        if ($request->has('active_token')) {
            $active_token = $request->active_token;
            if (Order::where([['active_token', '=', $active_token],['is_active','=', '0']])->count() > 0) {
                $active_order = Order::where('active_token', $active_token)->first()->update([
                    'is_active' => '1',
                    'status' => $this->processing_status,
                ]);
                return redirect('/home')->with('notification', 'Bạn đã đặt hàng thành công');
            } else {
                return redirect('/home')->with('alert', 'Quá trình xác nhận đơn hàng của bạn bị lỗi. Chúng tôi rất xin lỗi vì sự bất tiện này');
            }
        }
        return redirect('/home');
    }

    function cancel(Request $request)
    {
        if ($request->has('active_token')) {
            $active_token = $request->active_token;
            if (Order::where('active_token', $active_token)->count() > 0) {
                $active_order = Order::where('active_token', $active_token)->first()->update([
                    'is_active' => '1',
                    'status' => $this->canceled_status,
                ]);
                return redirect('/home')->with('notification', 'Bạn đã huỷ đơn hàng thành công');
            } else {
                return redirect('/home')->with('alert', 'Quá trình huỷ đơn hàng của bạn bị lỗi. Chúng tôi rất xin lỗi vì sự bất tiện này');
            }
        }
        return redirect('/home');
    }

    function detail($id)
    {
        $shipping_info = Order::join('shipping','orders.id','=','shipping.order_id')
        ->select('orders.id','orders.total','orders.status','orders.updated_at','shipping.fullname','shipping.email','shipping.address','shipping.phone','shipping.note')
        ->where('orders.id',$id)
        ->first();
        $order_detail = OrderDetail::join('products','products.id','=','order_details.product_id')
        ->leftJoin('product_cats','products.product_cat_id','=','product_cats.id')
        ->join('product_thumbs','products.id','=','product_thumbs.product_id')
        ->select('order_details.*','products.name as productName','products.price','product_cats.name as productCatName','product_thumbs.img_path')
        ->where([
            ['order_details.order_id',$id],
            ['product_thumbs.order_id',0],
        ])
        ->orderBy('products.name')
        ->get();
        return view('admin.order.detail',compact('shipping_info','order_detail'));
    }

    function update(Request $request, $id)
    {
        if($request->has('status_update_btn')){
            $status = $request->order_status;
            Order::find($id)->update([
                'status' => $status,
            ]);
            return redirect(route('order_list'))->with('status', 'Trạng thái đơn hàng được cập nhật thành công');
        }else return redirect('/dashboard');
    }

    function delete($id)
    {
        $order = Order::withTrashed()->find($id);
        if ($order->trashed()) {
            $order->forceDelete();
            return redirect(route('order_list'))->with('status', 'Đơn hàng đã bị xoá vĩnh viễn');
        }
        Order::destroy($id);
        return redirect(route('order_list'))->with('status', 'Đơn hàng đã được chuyển vào thùng rác');
    }

    function restore($id)
    {
        $order = Order::onlyTrashed()->find($id)->restore();
        return redirect(route('order_list'))->with('status', 'Đơn hàng đã được khôi phục');
    }

    function action(Request $request)
    {
        if ($request->has('act_btn')) {
            $list_check = $request->input('list_check');
            if ($list_check) {
                if (!empty($list_check)) {
                    $act = $request->input('act');
                    if ($act == 'forceDelete') {
                        $deleted_orders = Order::withTrashed()->whereIn('id', $list_check)->get();

                        foreach ($deleted_orders as $order) {
                            $order->forceDelete();
                        }

                        return redirect(route('order_list'))->with('status', 'Đơn hàng đã bị xoá vĩnh viễn');
                    }
                    if ($act == 'delete') {
                        Order::destroy($list_check);
                        return redirect(route('order_list'))->with('status', 'Đơn hàng đã được chuyển vào thùng rác');
                    }
                    if ($act == 'restore') {
                        Order::withTrashed()->whereIn('id', $list_check)->restore();
                        return redirect(route('order_list'))->with('status', 'Đơn hàng đã được khôi phục');
                    }
                    if ($act == "") {
                        return redirect(route('order_list'))->with('status', 'Bạn chưa chọn hành động cần thực hiện');
                    }
                }
            } else
                return redirect(route('order_list'))->with('status', 'Không có phẩn tử nào được chọn');
        }
    }

}
