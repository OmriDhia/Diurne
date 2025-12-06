<?php

declare(strict_types=1);

namespace App\Common\Bus\Query;

use Symfony\Contracts\Service\Attribute\Required;
use Psr\Cache\CacheItemPoolInterface;


trait CacheableQueryHandlerTrait
{
    private CacheItemPoolInterface $cache;

    #[Required]
    public function setCache(CacheItemPoolInterface $cache): void
    {
        $this->cache = $cache;
    }

    /**
     * Retrieves data from the cache or fetches it using the provided callback.
     *
     * @param string $cacheKey The cache key to use
     * @param callable $fetchData Callback to fetch the data if not in cache
     * @param int $ttl Time to live in seconds (default: 3600)
     * @return array The cached or freshly fetched data
     */
    protected function getCachedResult(string $cacheKey, callable $fetchData, int $ttl = 3600): array
    {
        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $data = $fetchData();

        $cacheItem->set($data);
        $cacheItem->expiresAfter($ttl);
        $this->cache->save($cacheItem);

        return $data;
    }

    /**
     * Clears the cache for a specific cache key.
     *
     * @param string $cacheKey The cache key to clear
     * @return bool True if the cache was successfully cleared, false otherwise
     */
    protected function clearCache(string $cacheKey): bool
    {
        return $this->cache->deleteItem($cacheKey);
    }
}
