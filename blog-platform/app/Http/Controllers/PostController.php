<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class PostController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'post_id' => 'required',
            'slug' => 'required',
            'excerpt' => 'nullable',
            'content' => 'required',
            'featured_image' => 'nullable',
            'user_id' => 'required',
            'category_id' => 'required',
            'status' => 'required',
            'published_at' => 'required',
            'meta_description' => 'required',
            'meta_keywords' => 'required',

        ]);

        $data = Post::create([
            'title' => $request->title,
            'post_id' => $request->post_id,
            'slug' => $request->slug,
            'excerpt' => $request->content,
            'content' => $request->excerpt,
            'featured_image' => $request->featured_image,
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'published_at' => $request->published_at,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords
        ]);

        return response()->json([
            'message' => "Successfully created post",
            'data' => $data
        ]);

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $filename = time() . '_' . $image->getClientOriginalName();

            // Optimize and store image
            $img = Image::make($image)
                ->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('jpg', 80);

            Storage::put('public/posts/' . $filename, $img);
            $post->featured_image = 'posts/' . $filename;
        }

        $post->save();


    }

    public function edit(Post $post)
    {
        // Check if user is allowed to edit this post
        if (auth()->user()->cannot('edit', $post)) {
            abort(403);
        }

        $categories = Category::all();
        $tags = Tag::all();

        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        // Check if user is allowed to edit this post
        if (auth()->user()->cannot('update', $post)) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'featured_image' => 'nullable|image|max:2048',
            'excerpt' => 'nullable|max:255',
            'is_published' => 'boolean',
        ]);

        $post->fill($request->except('featured_image', 'tags'));

        // Update slug if title changed
        if ($post->isDirty('title')) {
            $post->slug = str::slug($request->title);
        }


        if ($request->is_published && !$post->is_published) {
            $post->published_at = now();
        }

        // Handle featured image
        if ($request->hasFile('featured_image')) {

            if ($post->featured_image) {
                Storage::delete('public/' . $post->featured_image);
            }

            $image = $request->file('featured_image');
            $filename = time() . '_' . $image->getClientOriginalName();

            // Optimize and store image
            $img = Image::make($image)
                ->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('jpg', 80);

            Storage::put('public/posts/' . $filename, $img);
            $post->featured_image = 'posts/' . $filename;
        }

        $post->save();


        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }


        Cache::forget('latest_posts');
        Cache::forget('post_' . $post->id);

        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Post updated successfully.');
    }

    public function delete(Post $post)
    {

        if (auth()->user()->cannot('delete', $post)) {
            abort(403);
        }

        // Delete featured image if exists
        if ($post->featured_image) {
            Storage::delete('public/' . $post->featured_image);
        }

        $post->delete();

        // Clear cache for posts
        Cache::forget('latest_posts');
        Cache::forget('post_' . $post->id);

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}
