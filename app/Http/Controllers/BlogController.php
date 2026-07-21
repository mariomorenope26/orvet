<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        return view('blog.index', [
            'posts' => BlogPost::published()->paginate(9),
        ]);
    }

    public function show(BlogPost $post)
    {
        abort_unless($post->is_published, 404);

        return view('blog.show', [
            'post' => $post,
            'recent' => BlogPost::published()->where('id', '!=', $post->id)->take(5)->get(),
        ]);
    }
}
