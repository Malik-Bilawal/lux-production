<?php
namespace App\Observers;

use Illuminate\Support\Facades\Cache;

class CachingObserver
{
    protected $cacheKeys = [];
    protected $cacheTags = [];

    public function __construct(array $cacheKeys = [], array $cacheTags = [])
    {
        $this->cacheKeys = $cacheKeys;
        $this->cacheTags = $cacheTags;
    }

    public function saved($model)
    {
        $this->clearCache();
    }

    public function deleted($model)
    {
        $this->clearCache();
    }

    protected function clearCache()
    {
        // Flush cache keys
        foreach ($this->cacheKeys as $key) {
            Cache::forget($key);
        }

        // Flush cache tags (works in Redis or Memcached)
        foreach ($this->cacheTags as $tag) {
            if (Cache::getStore()->supportsTags()) {
                Cache::tags($tag)->flush();
            }
        }
    }
}
