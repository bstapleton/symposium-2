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

    public function update(array $data, int $id)
    {
        // TODO: Implement update() method.
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
