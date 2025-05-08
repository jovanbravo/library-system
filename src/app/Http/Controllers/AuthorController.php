<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class AuthorController extends Controller
{
    /**
     * Get All Authors Paginated
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $per_page = $this->request->get('per_page', 15);
        $name_filter= $this->request->get('name');

        return $this->service->get_paginated_data(
            filters: ['name' => $name_filter],
            per_page: $per_page
        );
    }

    /**
     * Store new Author
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        return $this->service->create_record($this->request->validated());
    }

    /**
     * Show Author Details
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return $this->service->get_by_id_with_relations(id: $id, relations: ['books']);
    }

    /**
     * Update Author
     *
     * @param int $id
     * @return JsonResponse
     */
    public function update(int $id): JsonResponse
    {
        return $this->service->update_record(id: $id, data: $this->request->validated());
    }

    /**
     * Destroy Author
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->service->delete_record(id: $id);
    }
}
