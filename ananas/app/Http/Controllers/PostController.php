<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Post;

class PostController extends Controller
{
    public function store()
    {
        if (\Auth::user())
        {
            $post=new Post();
            $attributes['content']=request('content');
            $attributes['user_id']=auth()->id();
            $attributes['author']=auth()->user()->name;
            $post=Post::create($attributes);
        }
        return redirect('/');
    }

    public function index()
    {
    	$posts=Post::orderBy('created_at', 'desc')->get()->take(20);
    	return view('welcome',[
            'posts' => $posts
        ]);
    }

    public function load()
    {
    	return view('ajax_more');
    }

    public function delete($postid)
    {
    	Post::find($postid)->delete();
    	$posts=Post::orderBy('created_at', 'desc')->get()->take(20);
    	return redirect('/');
    }
}
