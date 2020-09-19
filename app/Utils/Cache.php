<?php

namespace App\Utils;

use Illuminate\Support\Facades\Cache as FacadesCache;

class Cache extends FacadesCache
{
    public static function put(string $key, $value, $ttl = null): bool
    {
        $ttl = $ttl ?? config('cache.time');

        return parent::put($key, $value, $ttl);
    }
}
