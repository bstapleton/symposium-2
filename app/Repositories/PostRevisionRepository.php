<?php

namespace App\Repositories;

use App\Models\PostRevision;
use http\Exception\UnexpectedValueException;

class PostRevisionRepository implements IRevisionRepository
{

    public function all()
    {
        return PostRevision::all();
    }

    public function latest(int $postId)
    {
        return PostRevision::where('post_id', $postId)->orderBy('id', 'desc')->first();
    }

    public function show(int $id)
    {
        return PostRevision::find($id);
    }

    public function store(array $data)
    {
        $data['created_at'] = now();

        if (empty($data['title']) && empty($data['text'])) {
            throw new UnexpectedValueException();
        }

        return PostRevision::create($data);
    }

    public function destroy(int $id): int
    {
        return PostRevision::destroy($id);
    }

    public function older(int $id)
    {
        return PostRevision::where('id', '<', $id)->orderBy('id', 'desc')->get();
    }

    public function newer(int $id)
    {
        return PostRevision::where('id', '>', $id)->orderBy('id', 'asc')->get();
    }

    public function first(int $id)
    {
        if ($this->older($id)->count() === 0) {
            return null;
        }

        return $this->older($id)->reverse()->first();
    }

    public function previous(int $id)
    {
        return PostRevision::where('id', '<', $id)->orderBy('id', 'desc')->first();
    }

    public function next(int $id)
    {
        return PostRevision::where('id', '>', $id)->orderBy('id', 'asc')->first();
    }
}
