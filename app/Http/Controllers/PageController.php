<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Product;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => $request->slug]);

            return $next($request);
        });
    }

    function show($slug){
        $page = Page::where('slug',$slug)->first();

        $best_seller_products = Product::
        join('order_details','products.id','=','order_details.product_id')
        ->join('product_thumbs','product_thumbs.product_id','=','products.id')
        ->leftJoin('product_cats','products.product_cat_id','=','product_cats.id')
        ->selectRaw("products.id, sum(order_details.qty) as total_qty, product_cats.slug as product_cat_slug, product_cats.name as product_cat_name, products.name, products.price, product_thumbs.img_path")
        ->where([
            ['product_thumbs.order_id', 0],
            ['products.status', 1],
        ])
        ->groupBy(['products.id','product_cats.name','products.name','products.price','product_thumbs.img_path', 'product_cats.slug'])
        ->orderBy('order_details.qty')
        ->limit(8)
        ->get();

        return view('client.page.show',compact('page','best_seller_products'));
    }
}
