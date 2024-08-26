<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCat;
use App\Models\Post;
use App\Models\Slider;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'home']);

            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $best_seller_products = Product::
        join('order_details','products.id','=','order_details.product_id')
        ->join('product_thumbs','product_thumbs.product_id','=','products.id')
        ->leftJoin('product_cats','products.product_cat_id','=','product_cats.id')
        ->selectRaw(
            "products.id, 
            sum(order_details.qty) as total_qty, 
            product_cats.slug as product_cat_slug, 
            product_cats.name as product_cat_name, 
            products.name, 
            products.price, 
            product_thumbs.img_path"
        )
        ->where([
            ['product_thumbs.order_id', 0],
            ['products.status', 1],
        ])
        ->groupBy([
            'products.id',
            'product_cats.name',
            'products.name',
            'products.price',
            'product_thumbs.img_path', 
            'product_cats.slug'
        ])
        ->orderBy('total_qty')
        ->limit(8)
        ->get();

        $product_categories = ProductCat::get();
        $new_products_all = Product::leftJoin('product_cats','products.product_cat_id','=','product_cats.id')
        ->select('products.*','product_cats.slug as product_cat_slug','product_cats.name as product_cat_name')
        ->where('status','=',1)
        ->orderBy('created_at', 'desc')
        ->limit(8)
        ->get();

        $new_products_categories = ProductCat::limit(3)->get();
        $new_products = [
            0 => $new_products_all,
        ];
        foreach($new_products_categories as $product_cat){
            $new_products[] = Product::leftJoin('product_cats','products.product_cat_id','=','product_cats.id')
            ->select('products.*','product_cats.slug as product_cat_slug','product_cats.name as product_cat_name')
            ->where([
                ['status','=',1],
                ['product_cat_id','=',$product_cat->id]
                ])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();
        }

        $new_posts = Post::where('status','<>',0)->orderBy('created_at', 'desc')->limit(8)->get();

        $sliders = Slider::where('status','<>',0)->get();

        $feature_products = Product::leftJoin('product_cats','products.product_cat_id','=','product_cats.id')
        ->select('products.*','product_cats.slug as product_cat_slug','product_cats.name as product_cat_name')
        ->where('status','=',1)
        ->orderBy('view', 'desc')
        ->limit(12)
        ->get();

        return view('client.home',compact('product_categories','new_products','new_products_categories','new_posts', 'sliders','feature_products','best_seller_products'));
    }
}
