<?php

namespace App\Http\Controllers;
use App\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::paginate(2);    // Выводит все посты из бд или all();
       return view('front.index', ['posts'=>$posts]);
    }
}
