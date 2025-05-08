<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;

trait RedisCustomMethods
{
    /**
     * |-------------------------------------------------------------------------------
     * | REDIS CUSTOM METHODS - TRAIT
     * |-------------------------------------------------------------------------------
     * | Usage:
     * |  - Include this trait in Model which data need to be cached
     * |  - If you need a custom method for caching, you can extend this trait
     * |-------------------------------------------------------------------------------
     */

    /**
     * Get current model name and set name for cache
     *
     * @param int $id
     * @param string $suffix
     * @return string
     */
    private function get_model_cache_key(int $id, string $suffix = ''): string
    {
        $getModelNamespace = static::class;
        $getModelName = explode('\\', $getModelNamespace);
        $underscoredModelName = preg_replace('/([A-Z])/', '_$1', end($getModelName));
        $lowercaseModelName = strtolower(ltrim($underscoredModelName, '_'));

        return ($suffix != '')
            ? $lowercaseModelName . '_' . $suffix . '_' . $id
            : $lowercaseModelName . '_' . $id;
    }

    /**
     * Check if a cache exists
     *
     * @param int|null $id
     * @param string $suffix
     * @return mixed
     */
    public function check_cache(?int $id, string $suffix = ''): mixed
    {
        if (!$id) return null;

        return Redis::get($this->get_model_cache_key(id: $id, suffix: $suffix));
    }

    /**
     * Set cache
     *
     * @param int $id
     * @param mixed $data
     * @param string $suffix
     * @param float|int $cacheLifetime
     * @return void
     */
    public function set_cache(int $id, mixed $data, string $suffix = '', float|int $cacheLifetime = 24 * 3600): void
    {
        Redis::set($this->get_model_cache_key(id: $id, suffix: $suffix), $data);
        Redis::expire($this->get_model_cache_key(id: $id, suffix: $suffix), $cacheLifetime);
    }

    /**
     * Delete cache
     *
     * @param int $id
     * @param string $suffix
     * @return void
     */
    public function delete_cache(int $id, string $suffix = ''): void
    {
        Redis::del($this->get_model_cache_key(id: $id, suffix: $suffix));
    }
}
