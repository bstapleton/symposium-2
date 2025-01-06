@extends('layouts.default')

@section('content')
    <h1 class="text-xl font-semibold">Latest posts</h1>

    <ul class="list-none text-base mb-4 mt-2">
        @foreach($posts as $post)
            @include('shared.post-card', ['post' => $post])
        @endforeach
    </ul>
@endsection
