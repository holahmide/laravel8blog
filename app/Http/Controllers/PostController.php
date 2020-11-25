<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Mail\PostLiked;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    public function __construct() {
        $this->middleware(['auth'])->only('store','destroy');
    }

    public function index () {
        $posts = Post::with(['user','likes'])->orderBy('created_at','DESC')->paginate(5);
        return view('posts.index', [
            'posts' => $posts
        ]);
    }

    public function show (Post $post) {
        return view('posts.show', ['post' => $post]);
    }   

    public function store(Request $request) {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $request->user()->posts()->create([
            'body' => $request->body
        ]);

        return back();
    }

    public function destroy(Post $post, Request $request) {
        $this->authorize('delete', $post);
        $post->delete();

        return back();
    }

    public function storeLikes(Post $post, Request $request) {

        if($post->likedBy($request->user())) {
            return response(null, 409);
        }

        if(!$post->likes()->onlyTrashed()->where('user_id', $request->user()->id)->count()) {
            Mail::to($post->user)->send(new PostLiked(auth()->user(), $post));
        }

        $post->likes()->create([
            'user_id' => $request->user()->id
        ]);

        return back();
    }

    public function destroyLikes(Post $post, Request $request) {
        $request->user()->likes()->where('post_id', $post->id)->delete();

        return back();
    }
}
