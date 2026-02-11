<?php

namespace App\Interfaces;

interface RepositoryInterface
{
    public function getAll();
    public function create(array $request);
    public function getById(int $id);
    public function update(array $request, int $id);
    public function delete(int $id);
}
