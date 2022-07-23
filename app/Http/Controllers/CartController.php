<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //
    function list(){
        return view('client.cart.show');
    }

    function add(Request $request, $id){
        if($request->has('add_cart')){
            $product_color_count = Product::find($id)->product_colors()->count();
            if($product_color_count == 0){
                $product_color_validate = [];
            }else{
                $product_color_validate = ['required'];

            }
            $request->validate(
                [
                    'num_order' => ['required','integer','min:1'],
                    'product_color' => $product_color_validate,
                ],
                [
                    'required' => ':attribute không được để trống',
                    'integer'=>':attribute phải là một số',
                    'num_order.min'=>'Giá trị đặt mua tối thiểu cho sản phẩm phải là 1',
                    'product_color.required'=>'Cần chọn màu cho sản phẩm',
                ],
                [
                    'num_order' => 'Số lượng sản phẩm',
                    'product_color'=>'Màu sản phẩm',
                ]
            );

            $product = Product::find($id);

            if($product->status == 2){
                return redirect()->back()->with('alert','Sản phẩm đã hết hàng');
            }

            if(is_null($product->product_cat_id)){
                $product_cat_slug = 'san-pham-khac';
            }else{
                $product_cat_slug = $product->product_category()->first()->slug;
            }

            $product_img_path = $product->product_thumbs->where('order_id',0)->first()->img_path;
            if(!is_null(Color::find($request->product_color)))
                $product_color = Color::find($request->product_color)->name;
            else $product_color = "Không có";
            Cart::add([
                'id'=>$product->id,
                'name'=>$product->name,
                'qty'=>$request->num_order,
                'price'=>$product->price,
                'options'=>[
                    'img_path'=>$product_img_path,
                    'color'=>$product_color,
                    'detail_url' => route('product_detail',[$product_cat_slug, $product->slug_convert($product->name),$product->id]),
                ],
            ]);
        }
        // return redirect(route('cart_list'));
        return redirect()->back()->with('notification','Đã thêm vào giỏ hàng');
    }

    function remove($rowId){
        Cart::remove($rowId);
        return redirect(route('cart_list'));
    }
    function destroy(){
        Cart::destroy();
        return redirect(route('cart_list'));
    }

    function updatePriceAjax(Request $request){
        $product_qty = $request->num_order;
        $product_id = $request->product_id;

        Cart::update($product_id,$product_qty);

        $product = Cart::get($product_id);

        return $data = array(
            "product_total_price" => number_format($product->total, 0, '') . 'đ',
            "cart_total" => Cart::total(0, '') . 'đ',
        );
    }
}
