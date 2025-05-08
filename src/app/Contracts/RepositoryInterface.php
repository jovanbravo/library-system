<?php

namespace App\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function get_all_paginated(array $filters = [], int $per_page = 15): LengthAwarePaginator;
    public function get_by_id(int $id): ?object;
    public function get_by_id_with_relations(int $id, array $relations): ?object;
    public function create(array $data): ?Model;
    public function update(int $id, array $data): ?bool;
    public function delete(int $id): ?bool;
}
