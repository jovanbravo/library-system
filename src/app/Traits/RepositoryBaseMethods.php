<?php

namespace App\Traits;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

trait RepositoryBaseMethods
{
    /**
     * Retrieves the model associated with the Repository class.
     *
     * @return Model
     * @throws Exception
     */
    private function get_model(): Model
    {
        if(!static::MODEL) {
            throw new Exception('Model is not set! Please add it to the Repository class.');
        }
        return new (static::MODEL);
    }

    /**
     * Get All Data Paginated
     *
     * @param array $filters
     * @param int $per_page
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function get_all_paginated(array $filters = [], int $per_page = 15): LengthAwarePaginator
    {
        return $this->get_model()
            ->when($filters, function ($query, $filters) {
                foreach ($filters as $key => $value) {
                    $query->orWhere($key, 'LIKE', '%'.$value.'%');
                }
            })
            ->paginate($per_page);
    }

    /**
     * Get Single Record By ID
     *
     * @param int $id
     * @return Model|null
     * @throws Exception
     */
    public function get_by_id(int $id): ?Model
    {
        return $this->get_model()->find($id);
    }

    /**
     * Get Single Record By ID with Relations
     *
     * @param int $id
     * @param array $relations
     * @return Model|null
     * @throws Exception
     */
    public function get_by_id_with_relations(int $id, array $relations): ?Model
    {
        return $this->get_model()->with($relations)->find($id);
    }

    /**
     * Create New Record
     *
     * @param array $data
     * @return Model|null
     * @throws Exception
     */
    public function create(array $data): ?Model
    {
        return $this->get_model()->create($data);
    }

    /**
     * Update Existing Record
     *
     * @param int $id
     * @param array $data
     * @return bool|null
     * @throws Exception
     */
    public function update(int $id, array $data): ?bool
    {
        return $this->get_model()->find($id)->update($data);
    }

    /**
     * Delete Record
     *
     * @param int $id
     * @return bool|null
     * @throws Exception
     */
    public function delete(int $id): ?bool
    {
        return $this->get_model()->destroy($id);
    }
}
