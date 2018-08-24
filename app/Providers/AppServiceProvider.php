<?php

namespace App\Providers;
use App\Post;
use App\Category;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        view()->composer('front.blog',function($view){
            $view->with('popularPosts', Post::orderBy('views','desc')->take(3)->get());
            $view->with('featuredPosts', Post::where('is_featured', 1)->take(3)->get());
            $view->with('recentPosts', Post::orderBy('date','desc')->take(4)->get());
            $view->with('categories', Category::all());
            
        });

        view()->composer('front.list',function($view){
            $view->with('popularPosts', Post::orderBy('views','desc')->take(3)->get());
            $view->with('featuredPosts', Post::where('is_featured', 1)->take(3)->get());
            $view->with('recentPosts', Post::orderBy('date','desc')->take(4)->get());
            $view->with('categories', Category::all());
            
        });

        view()->composer('front.register',function($view){
            $view->with('popularPosts', Post::orderBy('views','desc')->take(3)->get());
            $view->with('featuredPosts', Post::where('is_featured', 1)->take(3)->get());
            $view->with('recentPosts', Post::orderBy('date','desc')->take(4)->get());
            $view->with('categories', Category::all());
            
        });

        view()->composer('front.login',function($view){
            $view->with('popularPosts', Post::orderBy('views','desc')->take(3)->get());
            $view->with('featuredPosts', Post::where('is_featured', 1)->take(3)->get());
            $view->with('recentPosts', Post::orderBy('date','desc')->take(4)->get());
            $view->with('categories', Category::all());
            
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
