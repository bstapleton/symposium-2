<?php

namespace App\Repositories;

use App\Models\PostHistory;

class PostHistoryRepository implements IHistoryRepository
{

    public function all()
    {
        return PostHistory::all();
    }

    public function latest(int $postId)
    {
        return PostHistory::where('post_id', $postId)->orderBy('id', 'desc')->first();
    }

    public function show(int $id)
    {
        return PostHistory::find($id);
    }

    public function store(array $data)
    {
        $data['created_at'] = now();
        return PostHistory::create($data);
    }

    public function destroy(int $id): int
    {
        return PostHistory::destroy($id);
    }

    public function older(int $id)
    {
        return PostHistory::where('id', '<', $id)->orderBy('id', 'desc')->get();
    }

    public function newer(int $id)
    {
        return PostHistory::where('id', '>', $id)->orderBy('id', 'asc')->get();
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
        return PostHistory::where('id', '<', $id)->orderBy('id', 'desc')->first();
    }

    public function next(int $id)
    {
        return PostHistory::where('id', '>', $id)->orderBy('id', 'asc')->first();
    }
}
