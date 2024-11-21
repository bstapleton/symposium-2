<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository implements IEloquentRepository
{
    public function all()
    {
        return Post::all();
    }

    public function show(string $slug)
    {
        return Post::where('slug', $slug)->firstOrFail();
    }

    public function store(array $data)
    {
        return Post::create($data);
    }

    public function destroy(int $id)
    {
        return Post::destroy($id);
    }
}
