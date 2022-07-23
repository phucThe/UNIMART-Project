<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Page;
use App\Models\ProductCat;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Paginator::useBootstrap();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('client.layouts.navigation',function($view){
            $pages = Page::where('status','<>',0)->get();
            $view->with('pages', $pages);
        });

        View::composer('client.layouts.menu_respon',function($view){
            $product_categories = ProductCat::get();
            $pages = Page::where('status','<>',0)->get();
            $view->with(['product_categories' =>  $product_categories, 'pages' => $pages]);
        });
    }
}
