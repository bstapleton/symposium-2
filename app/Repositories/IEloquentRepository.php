<?php

namespace App\Repositories;

interface IEloquentRepository
{
    public function all();
    public function store(array $data);
    public function destroy(int $id);
}
