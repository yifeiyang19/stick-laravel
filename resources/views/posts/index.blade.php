@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Posts</h1>
        @foreach ($posts as $post)
            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="card-title">{{ $post->title }}</h2>
                    <p class="card-text">{{ $post->body }}</p>
                    @if($post->image_path)
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post Image" style="max-width: 100%;">
                    @endif
                    <p class="card-text"><small class="text-muted">Posted by {{ $post->user->name }}</small></p>
                    @if (Auth::check() && Auth::user()->id == $post->user_id)
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
