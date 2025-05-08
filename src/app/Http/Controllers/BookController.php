<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    /**
     * Get All Books Paginated
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $per_page = $this->request->get('per_page', 15);
        $filters = $this->request->only(['title', 'isbn', 'author_id']);

        return $this->service->get_paginated_data(filters: $filters, per_page: $per_page);
    }

    /**
     * Show Book Details
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return $this->service->get_by_id($id);
    }

    /**
     * Store new Book
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        return $this->service->create_record($this->request->validated());
    }

    /**
     * Update Book
     *
     * @param int $id
     * @return JsonResponse
     */
    public function update(int $id): JsonResponse
    {
        return $this->service->update_record(id: $id, data: $this->request->validated());
    }

    /**
     * Destroy Book
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->service->delete_record($id);
    }
}
