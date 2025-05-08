<?php

namespace App\Traits;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

trait RepositoryMethodsCache
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
     * @return object|null
     * @throws Exception
     */
    public function get_by_id(int $id): ?object
    {
        if($cached = $this->get_model()->check_cache($id))
        {
            return json_decode($cached);
        }

        $record =  $this->get_model()->find($id);

        if(!$record) {
            return null;
        }

        $this->get_model()->set_cache(id: $id, data: $record->toJson());

        return $record;
    }

    /**
     * Get Single Record By ID with Relations
     *
     * @param int $id
     * @param array $relations
     * @return object|null
     * @throws Exception
     */
    public function get_by_id_with_relations(int $id, array $relations): ?object
    {
        if($cached = $this->get_model()->check_cache($id))
        {
            return json_decode($cached);
        }

        $record = $this->get_model()->with($relations)->find($id);

        if(!$record) {
            return null;
        }

        $this->get_model()->set_cache(id: $id, data: $record->toJson());

        return $record;
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
        $create = $this->get_model()->create($data);

        $this->get_model()->set_cache(
            id: $create->id,
            data: $create->toJson()
        );

        return $create;
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
        $update = $this->get_model()->find($id);
        $updating = $update->update($data);
        $new_data = $update->fresh();

        if($update->check_cache($id)) {
            $update->delete_cache($id);
        }

        $update->set_cache(id: $new_data->id, data: $new_data->toJson());

        return $updating;
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
        $destroy =  $this->get_model()->destroy($id);

        if($this->get_model()->check_cache($id)){
            $this->get_model()->delete_cache($id);
        }

        return $destroy;
    }
}
