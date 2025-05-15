<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function add(Request $request)
    {

        $request->validate([
            'content' => 'required',
            'post_id' => 'required',
            'user_id' => 'required',
            'parent_id' => 'required',
            'is_approved' => 'required',
        ]);

        $data = Comment::create([
            'content' => $request->content,
            'post_id' => $request->post_id,
            'user_id' => $request->user_id,
            'parent_id' => $request->parent_id,
            'is_approved' => $request->is_approved
        ]);

        return response()->json([
            'message' => "Successfully created comment",
            'data' => $data
        ]);
    }



    public function delete($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found'
            ], 404);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment successfully deleted'
        ], 200);
    }
}
