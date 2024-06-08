<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 添加验证规则
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('img', 'public');
        }

        Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => auth()->id(),
            'image_path' => $imagePath,
        ]);

        return redirect()->route('posts.index');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);

        if (auth()->id() != $post->user_id) {
            return redirect()->route('posts.index')->with('error', 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 添加验证规则
        ]);

        $post = Post::findOrFail($id);

        if (auth()->id() != $post->user_id) {
            return redirect()->route('posts.index')->with('error', 'Unauthorized action.');
        }

        $imagePath = $post->image_path;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('img', 'public');
        }

        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('posts.index');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if (auth()->id() != $post->user_id) {
            return redirect()->route('posts.index')->with('error', 'Unauthorized action.');
        }

        $post->delete();

        return redirect()->route('posts.index');
    }
}

