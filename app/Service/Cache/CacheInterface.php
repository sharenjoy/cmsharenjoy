<?php namespace Impl\Service\Cache;

interface CacheInterface {

    /**
     * Retrieve data from cache
     *
     * @param string    Cache item key
     * @return mixed    PHP data result of cache
     */
    public function get($key);

    /**
     * Add data to the cache
     *
     * @param string    Cache item key
     * @param mixed     The data to store
     * @param integer   The number of minutes to store the item
     * @return mixed    $value variable returned for convenience
     */
    public function put($key, $value, $minutes=null);

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
    public function putPaginated($currentPage, $perPage, $totalItems, $items, $key, $minutes=null);

    /**
     * Test if item exists in cache
     * Only returns true if exists && is not expired
     *
     * @param string    Cache item key
     * @return bool     If cache item exists
     */
    public function has($key);

}