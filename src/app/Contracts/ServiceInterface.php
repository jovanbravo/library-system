<?php

namespace App\Contracts;

use Illuminate\Http\JsonResponse;

interface ServiceInterface
{
    public function get_paginated_data(array $filters = [], int $per_page = 15): JsonResponse;
    public function get_by_id(int $id): JsonResponse;
    public function get_by_id_with_relations(int $id, array $relations): JsonResponse;
    public function create_record(array $data): JsonResponse;
    public function update_record(int $id, array $data): JsonResponse;
    public function delete_record(int $id): JsonResponse;
}
