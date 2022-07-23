<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostPostCat;
use App\Models\Product;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'post']);

            return $next($request);
        });
    }

    function show(Request $request){
        $keyword = "";
        if($request->keyword) $keyword = $request->keyword;
        $posts = Post::where([['status','<>',0],['title','LIKE',"%{$keyword}%"]])->paginate(6)->appends('keyword',$keyword);

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

        return view('client.post.blog',compact('posts','best_seller_products'));
    }
    function detail($post_slug, $id){
        $post = Post::find($id);
        $post->increment('view', 1);
        $related_post_categories = PostPostCat::whereIn('post_cat_id', $post->post_categories()->select('post_cat_id'))->select('post_id')->groupBy('post_id')->get();
        $related_posts = Post::whereIn('id',$related_post_categories)->where('id','<>',$id)->limit(12)->get();

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

        return view('client.post.detail_blog',compact('post','related_posts','best_seller_products'));
    }
}
