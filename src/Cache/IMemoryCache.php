<?php

namespace MicrosoftStoreLib\Cache;

interface IMemoryCache
{
    public function get($key);
    // always overwrites value
    public function set($key, $value, $ttl);
}
