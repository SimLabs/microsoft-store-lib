<?php

namespace MicrosoftStoreLib\Cache;

class APCuCache implements IMemoryCache
{
    public function get($key)
    {
        $success = false;
        $result = apcu_fetch($key, $success);
        if ($success)
            return $result;
        return null;
    }

    public function set($key, $value, $ttl)
    {
        while (!apcu_add($key, $value, $ttl)) {
            apcu_delete($key);
        }
    }
}
