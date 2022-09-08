<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCat;
use App\Models\ProductBrand;
use App\Models\ProductThumb;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product']);

            return $next($request);
        });
    }

    function show(Request $request, $product_category_slug = null)
    {

        $keyword = '';
        $price_filter_value = "";
        $brand_filter = [];

        $product_filter_parameters = [];

        $product_categories = ProductCat::get();
        $product_brands = ProductBrand::get();

        if ($product_category_slug === "san-pham-khac") {
            $product_list = Product::leftJoin('product_cats', 'products.product_cat_id', '=', 'product_cats.id')
                ->leftJoin('product_brands', 'products.brand_id', '=', 'product_brands.id')
                ->select('products.*', 'product_cats.slug as product_cat_slug', 'product_cats.name as product_cat_name','product_brands.name as product_brand_name')
                ->where([
                    ['products.status', '<>', 0],
                    ['products.product_cat_id', '=', null],
                ]);
        } else {
            if ($product_category_slug == null) {
                $product_list = Product::leftJoin('product_cats', 'products.product_cat_id', '=', 'product_cats.id')
                    ->leftJoin('product_brands', 'products.brand_id', '=', 'product_brands.id')
                    ->select('products.*', 'product_cats.slug as product_cat_slug', 'product_cats.name as product_cat_name','product_brands.name as product_brand_name')
                    ->where([
                        ['products.status', '<>', 0],
                    ]);
            } else
                $product_list = Product::join('product_cats', 'products.product_cat_id', '=', 'product_cats.id')
                    ->leftJoin('product_brands', 'products.brand_id', '=', 'product_brands.id')
                    ->select('products.*', 'product_cats.slug as product_cat_slug', 'product_cats.name as product_cat_name','product_brands.name as product_brand_name')
                    ->where([
                        ['products.status', '<>', 0],
                        ['product_cats.slug', '=', $product_category_slug],
                    ]);
        }

        if($request->keyword){
            $keyword = $request->keyword;
            $product_list = $product_list->where('products.name', 'LIKE', "%{$keyword}%");
            $product_filter_parameters['keyword'] = $keyword;
        }

        if ($request->price_filter) {
            $price_filter_value = $request->price_filter;
            if ($price_filter_value == 1) {
                $product_list = $product_list->where('products.price', '<', 500000);
            }
            if ($price_filter_value == 2) {
                $product_list = $product_list->where([['products.price', '>', 500000], ['products.price', '<', 1000000]]);
            }
            if ($price_filter_value == 3) {
                $product_list = $product_list->where([['products.price', '>', 1000000], ['products.price', '<', 5000000]]);
            }
            if ($price_filter_value == 4) {
                $product_list = $product_list->where([['products.price', '>', 5000000], ['products.price', '<', 10000000]]);
            }
            if ($price_filter_value == 5) {
                $product_list = $product_list->where('products.price', '>', 10000000);
            }

            $product_filter_parameters['price_filter'] = $request->price_filter;
        }

        if ($request->orderby) {
            $orderby_value = $request->orderby;
            if ($orderby_value == 1) {
                $product_list = $product_list->orderBy('products.name', 'asc');
            }
            if ($orderby_value == 2) {
                $product_list = $product_list->orderBy('products.name', 'desc');
            }
            if ($orderby_value == 3) {
                $product_list = $product_list->orderBy('products.price', 'asc');
            }
            if ($orderby_value == 4) {
                $product_list = $product_list->orderBy('products.price', 'desc');
            }

            $product_filter_parameters['orderby'] = $request->orderby;
        }

        if ($request->brand_filter) {
            $brand_filter = $request->brand_filter;
            $product_list = $product_list->whereIn('product_brands.id', $brand_filter);

            $product_filter_parameters['brand_filter'] = $request->brand_filter;
        }

        // $product_list = $product_list->paginate(9)->appends(['keyword' => $keyword,'test'=>111]);
        $product_list = $product_list->paginate(9)->appends($product_filter_parameters);

        return view('client.product.product_categories', compact('product_categories', 'product_list', 'product_brands', 'brand_filter','product_category_slug'));
    }


    function detail($product_category_slug = null, $product_slug, $id)
    {
        $product = Product::find($id);

        $color_list = $product->product_colors()->select('colors.id as color_id','name')->get();

        $product->increment('view', 1);
        $product_thumb_list = $product->product_thumbs()->orderBy('order_id')->get();
        $product_categories = ProductCat::get();
        $product_cat_id = $product->product_cat_id;

        $same_category_products = Product::leftJoin('product_cats', 'products.product_cat_id', '=', 'product_cats.id')
        ->leftJoin('product_brands', 'products.brand_id', '=', 'product_brands.id')
        ->join('product_thumbs','products.id','=','product_thumbs.product_id')
        ->select('products.*', 'product_cats.slug as product_cat_slug', 'product_cats.name as product_cat_name','product_brands.name as product_brand_name','product_thumbs.img_path')
        ->where([
            ['products.status', '=', 1],
            ['products.id', '!=', $id],
            ['product_thumbs.order_id','=',0],
            ['product_cats.id','=', $product_cat_id]
        ])
        ->orderBy('view')
        ->orderBy('updated_at')
        ->limit(12)
        ->get();

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

        return view('client.product.product_detail', compact('product', 'product_thumb_list', 'product_categories', 'same_category_products','best_seller_products','product_category_slug', 'color_list'));
    }

    function ajaxDisplayColorImage(Request $request){
        $product_id = $request->product_id;
        $color_id = $request->color_id;

        $color_img = null;
        if(ProductThumb::where([['color_id', '=', $color_id],['product_id', '=', $product_id]])->count() > 0){
            $color_img = ProductThumb::where([['color_id', '=', $color_id],['product_id', '=', $product_id]])->first()->img_path;
        };

        return $color_img;



    }

}
