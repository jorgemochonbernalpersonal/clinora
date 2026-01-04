<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of published blog posts.
     */
    public function index()
    {
        $posts = Post::published()->paginate(12);

        return view('blog.index', compact('posts'));
    }

    /**
     * Display the specified blog post.
     */
    public function show(Post $post)
    {
        // Verificar que el post esté publicado
        if (!$post->is_published || !$post->published_at || $post->published_at->isFuture()) {
            abort(404);
        }

        // Incrementar contador de vistas
        $post->incrementViews();

        // Posts relacionados (2 posts recientes de la misma categoría o los más recientes)
        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
