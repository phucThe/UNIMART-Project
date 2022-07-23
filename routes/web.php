<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\AdminPostCatController;
use App\Http\Controllers\AdminProductCatController;
use App\Http\Controllers\AdminColorController;
use App\Http\Controllers\AdminProductBrandController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();
Route::get('/logout', [LoginController::class, 'logout']);

Route::middleware('auth')->group(function () {

    Route::view("/file-manager/tinymce5", 'layouts.file_manager')->name('file_manager');

    Route::middleware('CheckRole')->group(function () {
        // Admin Dashboard

        Route::get('/dashboard', [DashboardController::class, 'show']);
        Route::get('/', [DashboardController::class, 'show']);

        Route::middleware('CheckAdmin')->group(function () {
            // User
            Route::get('admin/user/list', [AdminUserController::class, 'list']);
            Route::get('admin/user/add', [AdminUserController::class, 'add']);
            Route::get('admin/user/delete/{id}', [AdminUserController::class, 'delete'])->name('delete_user');
            Route::post('admin/user/store', [AdminUserController::class, 'store']);
            Route::get('admin/user/action', [AdminUserController::class, 'action']);

            Route::get('admin/user/restore/{id}', [AdminUserController::class, 'restore'])->name('user_restore');
        });

        Route::middleware('CheckUser')->group(function(){
            Route::get('admin/user/edit/{id}', [AdminUserController::class, 'edit'])->name('user_edit');
            Route::post('admin/user/update/{id}', [AdminUserController::class, 'update']);
        });

        // Admin Product
        Route::get('admin/product/list/{status?}', [AdminProductController::class, 'list'])->name('product_list');
        Route::get('admin/product/add', [AdminProductController::class, 'add']);
        Route::post('admin/product/store', [AdminProductController::class, 'store'])->name('product_store');
        Route::get('admin/product/edit/{id}', [AdminProductController::class, 'edit'])->name('product_edit');
        Route::post('admin/product/update/{id}', [AdminProductController::class, 'update'])->middleware('CheckAdmin');
        Route::get('admin/product/delete/{id}', [AdminProductController::class, 'delete'])->name('product_delete')->middleware('CheckAdmin');
        Route::get('admin/product/restore/{id}', [AdminProductController::class, 'restore'])->name('product_restore');
        Route::get('admin/product/action', [AdminProductController::class, 'action'])->middleware('CheckAdmin');
        Route::post('admin/product/image/deleteImgWithAjax', [AdminProductController::class, 'deleteImgWithAjax']);
        // Product Color
        Route::get('admin/product/product-color/list', [AdminColorController::class, 'list'])->name('color-list');
        Route::post('admin/product/product-color/store', [AdminColorController::class, 'store'])->name('color-store');
        Route::get('admin/product/product-color/delete/{id}', [AdminColorController::class, 'delete'])->name('color-delete')->middleware('CheckAdmin');
        Route::get('admin/product/color/createColorList', [AdminColorController::class, 'createColorList']);

        // Product Brand
        Route::get('admin/product/product-brand/list', [AdminProductBrandController::class, 'list'])->name('product-brand-list');
        Route::post('admin/product/product-brand/store', [AdminProductBrandController::class, 'store'])->name('product-brand-store');
        Route::get('admin/product/product-brand/delete/{id}', [AdminProductBrandController::class, 'delete'])->name('product-brand-delete')->middleware('CheckAdmin');

        // Product Cat
        Route::get('admin/product/product-cat/list', [AdminProductCatController::class, 'list']);
        Route::post('admin/product/product-cat/store', [AdminProductCatController::class, 'store']);
        Route::get('admin/product/product-cat/edit/{id}', [AdminProductCatController::class, 'edit'])->name('product_cat_edit');
        Route::post('admin/product/product-cat/update/{id}', [AdminProductCatController::class, 'update'])->middleware('CheckAdmin');
        Route::get('admin/product/product-cat/delete/{id}', [AdminProductCatController::class, 'delete'])->name('product_cat_delete')->middleware('CheckAdmin');

        // Admin Post
        Route::get('admin/post/list/{status?}', [AdminPostController::class, 'list'])->name('post_list');
        Route::get('admin/post/add', [AdminPostController::class, 'add']);
        Route::post('admin/post/store', [AdminPostController::class, 'store']);
        Route::get('admin/post/edit/{id}', [AdminPostController::class, 'edit'])->name('post_edit');
        Route::post('admin/post/update/{id}', [AdminPostController::class, 'update'])->middleware('CheckAdmin');
        Route::get('admin/post/delete/{id}', [AdminPostController::class, 'delete'])->name('post_delete')->middleware('CheckAdmin');
        Route::get('admin/post/restore/{id}', [AdminPostController::class, 'restore'])->name('post_restore');
        Route::get('admin/post/action', [AdminPostController::class, 'action'])->middleware('CheckAdmin');
        // Post Cat
        Route::get('admin/post/post-cat/list', [AdminPostCatController::class, 'list']);
        Route::post('admin/post/post-cat/store', [AdminPostCatController::class, 'store']);
        Route::get('admin/post/post-cat/edit/{id}', [AdminPostCatController::class, 'edit'])->name('post_cat_edit');
        Route::post('admin/post/post-cat/update/{id}', [AdminPostCatController::class, 'update'])->middleware('CheckAdmin');
        Route::get('admin/post/post-cat/delete/{id}', [AdminPostCatController::class, 'delete'])->name('post_cat_delete')->middleware('CheckAdmin');

        // Admin Page
        Route::get('admin/page/list/{status?}', [AdminPageController::class, 'list'])->name('page_list');
        Route::get('admin/page/add', [AdminPageController::class, 'add']);
        Route::post('admin/page/store', [AdminPageController::class, 'store']);
        Route::get('admin/page/edit/{id}', [AdminPageController::class, 'edit'])->name('page_edit');
        Route::post('admin/page/update/{id}', [AdminPageController::class, 'update'])->middleware('CheckAdmin');
        Route::get('admin/page/delete/{id}', [AdminPageController::class, 'delete'])->name('page_delete')->middleware('CheckAdmin');
        Route::get('admin/page/restore/{id}', [AdminPageController::class, 'restore'])->name('page_restore');
        Route::get('admin/page/action', [AdminPageController::class, 'action'])->middleware('CheckAdmin');

        // Admin Slider
        Route::get('admin/slider/list/{status?}', [AdminSliderController::class, 'list'])->name('slider_list');
        Route::get('admin/slider/add', [AdminSliderController::class, 'add']);
        Route::post('admin/slider/store', [AdminSliderController::class, 'store']);
        Route::get('admin/slider/edit/{id}', [AdminSliderController::class, 'edit'])->name('slider_edit');
        Route::post('admin/slider/update/{id}', [AdminSliderController::class, 'update'])->middleware('CheckAdmin');
        Route::get('admin/slider/delete/{id}', [AdminSliderController::class, 'delete'])->name('slider_delete')->middleware('CheckAdmin');
        Route::get('admin/slider/restore/{id}', [AdminSliderController::class, 'restore'])->name('slider_restore');
        Route::get('admin/slider/action', [AdminSliderController::class, 'action'])->middleware('CheckAdmin');

        // Admin Order
        Route::get('admin/order/list/{status?}', [AdminOrderController::class, 'list'])->name('order_list');
        Route::get('admin/order/detail/{id}', [AdminOrderController::class, 'detail'])->name('order_detail');
        Route::post('admin/order/update/{id}', [AdminOrderController::class, 'update'])->name('order_update')->middleware('CheckAdmin');
        Route::get('admin/order/delete/{id}', [AdminOrderController::class, 'delete'])->name('order_delete')->middleware('CheckAdmin');
        Route::get('admin/order/restore/{id}', [AdminOrderController::class, 'restore'])->name('order_restore');
        Route::get('admin/order/action', [AdminOrderController::class, 'action'])->name('order_action')->middleware('CheckAdmin');
    });
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Product
Route::get('/san-pham/{product_category_slug?}', [ProductController::class, 'show'])->name('product');
Route::get('/san-pham/{product_category_slug?}/{product_name}.{id}.html', [ProductController::class, 'detail'])->name('product_detail');
Route::post('/product/ajaxDisplayColorImage',[ProductController::class,'ajaxDisplayColorImage']);

// Post
Route::get('/bai-viet', [PostController::class, 'show'])->name('blog-list');
Route::get('/bai-viet/{post_slug}.{id}.html', [PostController::class, 'detail'])->name('blog-detail');

// Page
Route::get('/trang/{slug}', [PageController::class, 'show'])->name('page_show');

//Cart
Route::get('/cart', [CartController::class, 'list'])->name('cart_list');
Route::post('/cart/add-{id}', [CartController::class, 'add'])->name('cart_add');
Route::post('/cart/updatePriceAjax', [CartController::class, 'updatePriceAjax']);
Route::get('/cart/remove/{rowId}', [CartController::class, 'remove'])->name('cart_remove');
Route::get('/cart/destroy', [CartController::class, 'destroy'])->name('cart_destroy');

// Checkout
Route::get('/thanh-toan', [CheckOutController::class, 'show'])->name('checkout_show');
Route::get('/thong-bao-don-hang-{order_id}', [CheckOutController::class, 'verify_order_messeage'])->name('checkout_message');

// Order
Route::post('/order/add', [AdminOrderController::class, 'store'])->name('order_add');
// Route::get('/order/confirm', [AdminOrderController::class, 'confirm'])->name('order_confirm');
// Route::get('/order/cancel', [AdminOrderController::class, 'cancel'])->name('order_cancel');

