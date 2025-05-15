<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function add(Request $request)
    {

        $request->validate([

            'name' => "required",
            'slug' => 'required'
        ]);

        $data = Tag::create([
            'name' => $request->name,
            'slug' => $request->slug
        ]);

        return response()->json([
            'message' => "Successfully added Tags",
            'data' => $data
        ]);
    }

    public function delete($id)
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json([
                'message' => 'Tag not found'
            ], 404);
        }

        $tag->delete();

        return response()->json([
            'message' => 'Tag successfully deleted'
        ], 200);
    }
}
