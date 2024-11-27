<?php

namespace App\Repositories;

interface IRevisionRepository extends IEloquentRepository
{
    public function older(int $id);
    public function newer(int $id);
    public function oldest(int $id);
    public function newest(int $id);
    public function previous(int $id);
    public function next(int $id);
}
