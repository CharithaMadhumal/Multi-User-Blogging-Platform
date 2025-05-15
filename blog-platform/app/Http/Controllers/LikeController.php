<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function add(Request $request)
    {

        $request->validate([

            "user_id" => "required",
            "post_id" => "required"
        ]);

        $data = Like::create([
            "user_id" => $request->user_id,
            "post_id" => $request->post_id

        ]);

        return response()->json([
            "message" => "Successfully created liked",
            "data" => $data
        ]);
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Post $post)
    {
        $like = $post->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            // Unlike the post
            $like->delete();
            $message = 'Post unliked successfully.';
        } else {
            // Like the post
            $post->likes()->create([
                'user_id' => auth()->id(),
            ]);
            $message = 'Post liked successfully.';
        }

        if (request()->expectsJson()) {
            return response()->json([
                'likes_count' => $post->likes()->count(),
                'is_liked' => !$like,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}

