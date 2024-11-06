<?php

namespace App\Repositories;

use App\Models\PostHistory;

class PostHistoryRepository implements IHistoryRepository
{

    public function all()
    {
        return PostHistory::all();
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
}
