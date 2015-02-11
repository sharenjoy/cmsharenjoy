<?php namespace Impl\Service\Cache;

use Illuminate\Cache\CacheManager;

class LaravelCache implements CacheInterface {

    protected $cache;
    protected $cachekey;
    protected $minutes;

    public function __construct(CacheManager $cache, $cachekey, $minutes=null)
    {
        $this->cache = $cache;
        $this->cachekey = $cachekey;
        $this->minutes = $minutes;
    }

    /**
     * Retrieve data from cache
     *
     * @param string    Cache item key
     * @return mixed    PHP data result of cache
     */
    public function get($key)
    {
        return $this->cache->section($this->cachekey)->get($key);
    }

    /**
     * Add data to the cache
     *
     * @param string    Cache item key
     * @param mixed     The data to store
     * @param integer   The number of minutes to store the item
     * @return mixed    $value variable returned for convenience
     */
    public function put($key, $value, $minutes=null)
    {
        if( is_null($minutes) )
        {
            $minutes = $this->minutes;
        }

        return $this->cache->section($this->cachekey)->put($key, $value, $minutes);
    }

    /**
     * Add data to the cache
     * taking pagination data into account
     *
     * @param integer   Page of the cached items
     * @param integer   Number of results per page
     * @param integer   Total number of possible items
     * @param mixed     The actual items for this page
     * @param string    Cache item key
     * @param integer   The number of minutes to store the item
     * @return mixed    $items variable returned for convenience
     */
    public function putPaginated($currentPage, $perPage, $totalItems, $items, $key, $minutes=null)
    {
        $cached = new \StdClass;

        $cached->currentPage = $currentPage;
        $cached->items = $items;
        $cached->totalItems = $totalItems;
        $cached->perPage = $perPage;

        $this->put($key, $cached, $minutes);

        return $cached;
    }

    /**
     * Test if item exists in cache
     * Only returns true if exists && is not expired
     *
     * @param string    Cache item key
     * @return bool     If cache item exists
     */
    public function has($key)
    {
        return $this->cache->section($this->cachekey)->has($key);
    }

}