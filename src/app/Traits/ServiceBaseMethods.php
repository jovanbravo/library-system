<?php

namespace App\Traits;

use App\Http\Responses\CustomResponse;
use Illuminate\Http\JsonResponse;

trait ServiceBaseMethods
{
    /**
     * Get All Data Filtered And Paginated
     *
     * @param array $filters
     * @param int $per_page
     * @return JsonResponse
     */
    public function get_paginated_data(array $filters = [], int $per_page = 15): JsonResponse
    {
        try {
            $data = $this->repository->get_all_paginated(filters: $filters, per_page: $per_page);
            $resource = static::INDEX_RESOURCE::collection($data)->response()->getData();

            return CustomResponse::successRequest(data: $resource);
        } catch (\Throwable $throwable) {
            return CustomResponse::badRequest($throwable->getMessage());
        }
    }

    /**
     * Get Single Record By ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function get_by_id(int $id): JsonResponse
    {
        try {
         $data = $this->repository->get_by_id($id);
         if(!$data) {
             return CustomResponse::badRequest('Record not found!');
         }
         $resource = new (static::DETAIL_RESOURCE)($data);

         return CustomResponse::successRequest(data: $resource);
        } catch (\Throwable $throwable) {
            return CustomResponse::badRequest($throwable->getMessage());
        }
    }

    /**
     * Get By ID with Relations
     *
     * @param int $id
     * @param array $relations
     * @return JsonResponse
     */
    public function get_by_id_with_relations(int $id, array $relations): JsonResponse
    {
        try {
            $data = $this->repository->get_by_id_with_relations($id, relations: $relations);

            if(!$data) {
                return CustomResponse::badRequest('Record not found!');
            }

            $resource = new (static::DETAIL_RESOURCE)($data);

            return CustomResponse::successRequest(data: $resource);
        } catch (\Throwable $throwable) {
            return CustomResponse::badRequest($throwable->getMessage());
        }
    }

    /**
     * Create a new record
     *
     * @param array $data
     * @return JsonResponse
     */
    public function create_record(array $data): JsonResponse
    {
        try {
         $created_data = $this->repository->create($data);

         return CustomResponse::successRequest(
             message: 'Record Created Successfully',
             data: $created_data
         );
        } catch (\Throwable $throwable) {
            return CustomResponse::badRequest($throwable->getMessage());
        }
    }

    /**
     * Update Record
     *
     * @param int $id
     * @param array $data
     * @return JsonResponse
     */
    public function update_record(int $id, array $data): JsonResponse
    {
        if(! $this->repository->get_by_id($id)) {
            return CustomResponse::badRequest('Record not found!');
        }

        try {
            $this->repository->update($id, $data);
            return CustomResponse::successRequest(
                message: 'Record Updated Successfully',
                data: ['id' => $id]
            );
        } catch (\Throwable $throwable) {
            return CustomResponse::badRequest($throwable->getMessage());
        }
    }

    /**
     * Delete Record
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete_record(int $id): JsonResponse
    {
        try {
            $this->repository->delete($id);

            return CustomResponse::successRequest(
                message: 'Record Deleted Successfully',
                data: ['deleted_id' => $id]
            );
        } catch (\Throwable $throwable) {
            return CustomResponse::badRequest($throwable->getMessage());
        }
    }
}
