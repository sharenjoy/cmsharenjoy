<?php namespace Sharenjoy\Cmsharenjoy\Filters;

use Illuminate\Routing\Route;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Str, Cache;

class CacheFilter {

    public function fetch(Route $route, Request $request)
    {
        $key = $this->makeCacheKey($request->url());

        if (Cache::has($key)) return Cache::get($key);
    }

    public function put(Route $route, Request $request, Response $response)
    {
        $key = $this->makeCacheKey($request->url());

        // cache 60 minutes
        if ( ! Cache::has($key)) return Cache::put($key, $response->getContent(), 60);
    }

    public function makeCacheKey($url)
    {
        return 'route_'. Str::slug($url);
    }

}