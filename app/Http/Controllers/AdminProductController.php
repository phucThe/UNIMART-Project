<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductThumb;
use App\Models\ProductCat;
use App\Models\Color;
use App\Models\ProductBrand;
use App\Models\ProductColor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    //
    // private const STATUS = ['Riêng tư','Công khai','Đang xử lý','Đang giao hàng','Hoàn thành','Đã huỷ'];

    private $private_status = 0;
    private $stocking_status = 1;
    private $out_of_stock_status = 2;
    private $trash = 3;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product']);

            return $next($request);
        });

        // Thiết lập session để add class active vào bên thanh sidebar
    }

    function list(Request $request, $status = null)
    {
        $keyword = '';
        $warning = "";
        $list_act = ['delete' => 'Chuyển vào thùng rác'];

        if ($request->keyword) $keyword = $request->keyword;

        $products = Product::where('name', 'LIKE', "%{$keyword}%")->paginate(10)->appends('keyword', $keyword);
        $warning = "Chuyển sản phẩm này vào thùng rác?";

        if (!is_null($status)) {
            if ($status != $this->trash) {
                $products = Product::where([['status', $status], ['name', 'LIKE', "%{$keyword}%"]])->paginate(10)->appends('keyword', $keyword);
            } else {
                $products = Product::onlyTrashed()->where('name', 'LIKE', "%{$keyword}%")->paginate(10)->appends('keyword', $keyword);
                $warning = "Xoá vĩnh viễn sản phẩm này?";
                $list_act = [
                    'forceDelete' => 'Xoá vĩnh viễn',
                    'restore' => 'Khôi phục'

                ];
            }
        }

        $count_products_active = Product::all()->count();
        $count_products_inactive = Product::onlyTrashed()->count();
        $count_products_stocking = Product::where('status', $this->stocking_status)->count();
        $count_products_out_of_stock = Product::where('status', $this->out_of_stock_status)->count();
        $count_products_private = Product::where('status', $this->private_status)->count();

        $count = [$count_products_active, $count_products_inactive, $count_products_out_of_stock, $count_products_stocking, $count_products_private];

        $product_thumbs = new ProductThumb();
        return view('admin.product.list', compact('products', 'product_thumbs', 'count', 'warning', 'status', 'list_act'));
    }

    function add()
    {
        $product_brand_list = ProductBrand::get();
        $product_cat_list = ProductCat::get_cat_tree();
        $product_color_list = Color::get();
        return view('admin.product.add', compact('product_cat_list', 'product_brand_list', 'product_color_list'));
    }

    function store(Request $request)
    {
        $request->validate(
            [
                "name" => ['required', 'max:255'],
                "price" => ['required', 'integer'],
                "desc" => ['required'],
                "detail" => ['required'],
                "product_thumb" => ['required', 'max:8',],
                "product_thumb.*" => ['distinct', 'image', 'mimes:png,jpeg,jpg'],
                "product_categories_selector" => ['required'],
                "product_brands_selector" => ['required'],
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute chỉ được nhập tối đa :max ký tự',
                'integer' => ':attribute phải là kiểu số',
                'product_thumb.required' => 'Chưa có hình ảnh cho sản phẩm',
                'product_thumb.max' => 'Chỉ được phép nhập tối đa :max hình ảnh cho sản phẩm',
                'product_thumb.*.distinct' => ":attribute bị trùng",
                'product_thumb.*.image' => ':attribute phải thuộc định dạng ảnh',
                'product_thumb.*.mimes' => 'Chỉ chấp nhận định dạng ảnh jpg hoặc png',


            ],
            [
                'name' => 'Tên sản phẩm',
                'price' => 'Giá sản phẩm',
                'desc' => 'Mô tả sản phẩm',
                'detail' => 'Chi tiết sản phẩm',
                'product_thumb' => 'Hình ảnh sản phẩm',
                'product_thumb.*' => 'Hình ảnh sản phẩm',
                "product_categories_selector" => "Danh mục sản phẩm",
                "product_brands_selector" => "Thương hiệu sản phẩm",
            ]
        );

        $product_category = $request->product_categories_selector;
        if ($product_category == 0) {
            $product_category = null;
        }
        $product_brand = $request->product_brands_selector;
        if ($product_brand == 0) {
            $product_brand = null;
        }

        $product = Product::create(
            [

                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'desc' => $request->input('desc'),
                'detail' => $request->input('detail'),
                'status' => $request->product_status,
                'product_cat_id' => $product_category,
                'brand_id' => $product_brand,
            ]
        );

        $color_list = $request->product_color_selector;
        if (!empty($color_list)) {
            foreach ($color_list as $color) {
                ProductColor::create([
                    'color_id' => $color,
                    'product_id' => $product->id,
                ]);
            }
        }

        $files = $request->file('product_thumb');

        $i = 0;
        $order_id_list = $request->order_id;
        $img_color_list = $request->color_selector;
        $count = count($order_id_list);

        for ($i = 0; $i < $count; $i++) {

            if ($order_id_list[$i] == $count) {
                $order_id = $count;
            } else {
                $order_id = $order_id_list[$i];
            }

            if ($img_color_list[$i] == 0) {
                $color = null;
            } else {
                $color = $img_color_list[$i];
            }


            $file_name = Str::random(10) . date("Y-m-d-His") . "." . $files[$i]->getClientOriginalExtension();
            $file_path = $files[$i]->storeAs(
                'product_images',
                $file_name,
                'google'
            );
            Storage::disk('google')->setVisibility($file_path, "public");
            $file_metadata = Storage::disk('google')->getAdapter()->getMetadata($file_path);
            $file_id = $file_metadata["extra_metadata"]["id"];
            $file_url = Product::getFileURL($file_id);

            ProductThumb::create([
                'img_name' => $file_path,
                'img_path' => $file_url,
                'user_id' => Auth::user()->id,
                'product_id' => $product->id,
                'order_id' => $order_id,
                'color_id' => $color,
            ]);
        };



        // ============================================================ CẬP NHẬT LẠI CHỈ SỐ ===============================================================

        $product_img_list = Product::find($product->id)->product_thumbs()->orderBy('order_id')->orderBy('updated_at')->get();
        $key = 0;
        foreach ($product_img_list as $img) {
            $img->update([
                "order_id" => $key,
            ]);
            $key++;
        }

        return redirect('admin/product/list')->with('status', 'Đã thêm thành công');
    }
    // End STORE

    function edit($id)
    {
        $user_id = Auth::user()->id;
        $deleted_product_thumbs_list = Product::find($id)->product_thumbs()->onlyTrashed()->where('user_id', $user_id)->get();
        if (!empty($deleted_product_thumbs_list)) {
            foreach ($deleted_product_thumbs_list as $img) {
                $img->restore();
            }
        }
        $product = Product::find($id);
        $product_img = array(
            'product_img_list' => Product::find($id)->product_thumbs()->orderBy('order_id')->orderBy('updated_at')->get(),
            'product_img_count' => Product::find($id)->product_thumbs()->count(),

        );
        $product_brand_list = ProductBrand::get();
        $product_cat_list = ProductCat::get_cat_tree();
        $colors = Color::get();
        $product_color_list = Product::find($id)->product_colors()->get();
        return view('admin.product.update', compact('product', 'product_img', 'id', 'product_brand_list', 'product_cat_list', 'colors', 'product_color_list'));
    }
    // END EDIT

    function update(Request $request, $id)
    {

        $product_thumbs_max_qty = 8;

        $product_thumbs_qty = ProductThumb::where('product_id', $id)->count();
        if ((!$request->hasFile('product_thumb')) && ($product_thumbs_qty == 0)) {
            $product_thumbs_validation = ['required', 'max:8',];
            $product_thumbs_validation_array = ['distinct', 'image', 'mimes:png,jpeg,jpg'];
        }
        if ((!$request->hasFile('product_thumb')) && ($product_thumbs_qty > 0)) {
            $product_thumbs_validation = ["max:" . ($product_thumbs_max_qty - $product_thumbs_qty),];
            $product_thumbs_validation_array = [];
        }
        if (($request->hasFile('product_thumb')) && ($product_thumbs_qty > 0)) {
            $product_thumbs_validation = ["max:" . ($product_thumbs_max_qty - $product_thumbs_qty),];
            $product_thumbs_validation_array = ['distinct', 'image', 'mimes:png,jpeg,jpg'];
        }

        if (($request->hasFile('product_thumb')) && ($product_thumbs_qty == 0)) {
            $product_thumbs_validation = ['required', 'max:8',];
            $product_thumbs_validation_array = ['distinct', 'image', 'mimes:png,jpeg,jpg'];
        }

        $request->validate(
            [
                "name" => ['required', 'max:255'],
                "price" => ['required', 'integer'],
                "desc" => ['required'],
                "detail" => ['required'],
                "product_thumb" => $product_thumbs_validation,
                "product_thumb.*" => $product_thumbs_validation_array,
                "product_categories_selector" => ['required'],
                "product_brands_selector" => ['required'],
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute chỉ được nhập tối đa :max ký tự',
                'integer' => ':attribute phải là kiểu số',
                'product_thumb.required' => 'Chưa có hình ảnh cho sản phẩm',
                'product_thumb.max' => 'Chỉ được phép nhập tối đa ' . $product_thumbs_max_qty . ' hình ảnh cho sản phẩm',
                'product_thumb.*.distinct' => ":attribute bị trùng",
                'product_thumb.*.image' => ':attribute phải thuộc định dạng ảnh',
                'product_thumb.*.mimes' => 'Chỉ chấp nhận định dạng ảnh jpg hoặc png',
            ],
            [
                'name' => 'Tên sản phẩm',
                'price' => 'Giá sản phẩm',
                'desc' => 'Mô tả sản phẩm',
                'detail' => 'Chi tiết sản phẩm',
                'product_thumb' => 'Hình ảnh sản phẩm',
                'product_thumb.*' => 'Hình ảnh sản phẩm',
                "product_categories_selector" => "Danh mục sản phẩm",
                "product_brands_selector" => "Thương hiệu sản phẩm",
            ]
        );

        $user_id = Auth::user()->id;
        $deleted_product_thumbs_list = Product::find($id)->product_thumbs()->onlyTrashed()->where('user_id', $user_id)->get();
        if ($deleted_product_thumbs_list->count() > 0) {
            foreach ($deleted_product_thumbs_list as $img) {
                Storage::disk('google')->delete($img->img_name);
                $img->forceDelete();
            }
        }

        $product_category = $request->product_categories_selector;
        if ($product_category == 0) {
            $product_category = null;
        }
        $product_brand = $request->product_brands_selector;
        if ($product_brand == 0) {
            $product_brand = null;
        }

        Product::find($id)->update(
            [
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'desc' => $request->input('desc'),
                'detail' => $request->input('detail'),
                'status' => $request->product_status,
                'product_cat_id' => $product_category,
                'brand_id' => $product_brand,
            ]
        );

        $color_list = $request->product_color_selector;
        if (!empty($color_list)) {
            $color_list_qty = count($color_list);
            $product_colors = ProductColor::where('product_id', $id)->get();
            $product_colors_qty = ProductColor::where('product_id', $id)->count();

            if ($color_list_qty < $product_colors_qty) {
                $product_colors_skip = $color_list_qty;
                $product_colors_take = $product_colors_qty - $color_list_qty;
                $deleted_product_colors = ProductColor::where('product_id', $id)->skip($product_colors_skip)->take($product_colors_take)->get();
                foreach ($deleted_product_colors as $del_product_color) {
                    $del_product_color->delete();
                }
            }
            for ($i = 0; $i < $color_list_qty; $i++) {
                if (isset($product_colors[$i])) {
                    $product_colors[$i]->update([
                        'color_id' => $color_list[$i],
                    ]);
                } else {
                    ProductColor::create([
                        'color_id' => $color_list[$i],
                        'product_id' => $id,
                    ]);
                }
            }
        } else {
            $deleted_product_colors = ProductColor::where('product_id', $id)->get();
            foreach ($deleted_product_colors as $del_product_color) {
                $del_product_color->delete();
            }
        }

        $order_id_list = $request->order_id;
        $order_id_count = count($order_id_list);
        if ($request->hasFile('product_thumb')) {

            $uploaded_files = $request->file('product_thumb');
            foreach ($uploaded_files as $file) {
                $file_name = Str::random(10) . date("Y-m-d-His") . "." . $file->getClientOriginalExtension();

                $file_path = $file->storeAs(
                    'product_images',
                    $file_name,
                    'google'
                );
                Storage::disk('google')->setVisibility($file_path, "public");
                $file_metadata = Storage::disk('google')->getAdapter()->getMetadata($file_path);
                $file_id = $file_metadata["extra_metadata"]["id"];
                $file_url = Product::getFileURL($file_id);

                ProductThumb::create([
                    'img_name' => $file_path,
                    'img_path' => $file_url,
                    'user_id' => $user_id,
                    'product_id' => $id,
                    'order_id' => $order_id_count,
                ]);
            }
        }

        $i = 0;
        $img_color_list = $request->color_selector;
        $product_thumb_count = Product::find($id)->product_thumbs()->orderBy('order_id')->orderBy('id')->count();
        if ($product_thumb_count > 0) {
            $product_thumb_list = Product::find($id)->product_thumbs()->orderBy('order_id')->orderBy('id')->get();
            for ($i = 0; $i < $product_thumb_count; $i++) {
                if ($order_id_list[$i] == $order_id_count) {
                    $order_id = $order_id_count;
                } else {
                    $order_id = $order_id_list[$i];
                }

                if ($img_color_list[$i] == 0) {
                    $color = null;
                } else {
                    $color = $img_color_list[$i];
                }
                $product_thumb_list[$i]->update([
                    'order_id' => $order_id,
                    'color_id' => $color,
                ]);
            }
        }

        // ========================================================= Cập nhật lại chỉ số ===========================================================
        $key = 0;
        $product_thumb_list = Product::find($id)->product_thumbs()->orderBy('order_id')->orderBy('id')->get();
        foreach ($product_thumb_list as $img) {
            $img->update([
                "order_id" => $key,
            ]);
            $key++;
        }

        return redirect('admin/product/list')->with('status', 'Đã cập nhật thành công');
    }


    // END UPDATE

    function delete($id)
    {
        $product = Product::withTrashed()->find($id);

        if ($product->trashed()) {
            $deleted_product_thumbs_list = Product::onlyTrashed()->find($id)->product_thumbs()->withTrashed()->get();
            if (!empty($deleted_product_thumbs_list)) {
                foreach ($deleted_product_thumbs_list as $img) {
                    Storage::disk('google')->delete($img->img_name);
                    $img->forceDelete();
                }
            }
            $product->forceDelete();
            return redirect('admin/product/list')->with('status', 'Sản phẩm đã bị xoá vĩnh viễn');
        }
        Product::destroy($id);
        return redirect('admin/product/list')->with('status', 'Sản phẩm đã được chuyển vào thùng rác');
    }


    function deleteImgWithAjax(Request $request)
    {

        $img_src = $request->img_src;
        $user_id = Auth::user()->id;
        $product_img = ProductThumb::where([
            ['img_path', '=', $img_src],
            ['user_id', '=', $user_id],
        ])->delete();
    }

    // END DELETE

    function restore($id)
    {
        Product::onlyTrashed()->find($id)->restore();
        return redirect('admin/product/list')->with('status', 'Sản phẩm đã được khôi phục');
    }
    // END RESTORE

    private function show_array($array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if ($list_check) {
            if (!empty($list_check)) {
                $act = $request->input('act');
                if ($act == 'forceDelete') {
                    $deleted_product_list = Product::onlyTrashed()->whereIn('id', $list_check)->get();
                    // return $deleted_product_list;
                    foreach ($deleted_product_list as $deleted_product) {
                        $deleted_product_thumbs_list = $deleted_product->product_thumbs()->withTrashed()->get();
                        // $this->show_array($deleted_product_thumbs_list);
                        if (!empty($deleted_product_thumbs_list)) {
                            foreach ($deleted_product_thumbs_list as $img) {
                                Storage::disk('google')->delete($img->img_name);
                                $img->forceDelete();
                            }
                        }
                    }

                    Product::onlyTrashed()->whereIn('id', $list_check)->forceDelete();
                    return redirect('admin/product/list')->with('status', 'Sản phẩm đã bị xoá vĩnh viễn');
                }
                if ($act == 'delete') {
                    Product::destroy($list_check);
                    return redirect('admin/product/list')->with('status', 'Sản phẩm đã được chuyển vào thùng rác');
                }
                if ($act == 'restore') {
                    Product::onlyTrashed()->whereIn('id', $list_check)->restore();
                    return redirect('admin/product/list')->with('status', 'Sản phẩm đã được khôi phục');
                }
                if ($act == "") {
                    return redirect('admin/product/list')->with('status', 'Bạn chưa chọn hành động cần thực hiện');
                }
            }
        } else
            return redirect('admin/product/list')->with('status', 'Không có phẩn tử nào được chọn');
    }
}
