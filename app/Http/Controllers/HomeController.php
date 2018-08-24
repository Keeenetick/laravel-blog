<?php

namespace App\Http\Controllers;
use App\Post;
use App\Tag;
use App\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::paginate(2);    // Выводит все посты из бд или all();
        $popularPosts = Post::orderBy('views','desc')->take(3)->get();
        $featuredPosts = Post::where('is_featured', 1)->take(3)->get();
        $recentPosts = Post::orderBy('date','desc')->take(4)->get();
        $categories = Category::all();
        
       return view('front.index', [
           'posts'=>$posts,
           'popularPosts' => $popularPosts,
           'featuredPosts'=> $featuredPosts,
           'recentPosts'=>$recentPosts,
           'categories'=>$categories
       
       
       ]);                                    //Для просмотра самого blog.blade смотри Providers App
    }

    public function show($slug)
    {
        $post = Post::where('slug',$slug)->firstOrFail();
        return view('front.blog', compact('post'));
    }

    public function tag($slug)
    {
        $tag = Tag::where('slug' , $slug)->firstOrFail();
        $posts = $tag->posts()->paginate(2);
        return view('front.list', ['posts'=> $posts]);
    }

    public function category($slug)
    {
        $category = Category::where('slug' , $slug)->firstOrFail();
        $posts = $category->posts()->paginate(2);
        return view('front.list', ['posts'=> $posts]);
    }
}
