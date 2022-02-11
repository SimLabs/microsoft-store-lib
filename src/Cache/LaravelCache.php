<?php

namespace MicrosoftStoreLib\Cache;

use Illuminate\Support\Facades\Cache;

class LaravelCache implements IMemoryCache
{
    public function get($key)
    {
        return Cache::get($key);
    }

    public function set($key, $value, $ttl)
    {
        Cache::put($key, $value, $ttl);
    }
}
