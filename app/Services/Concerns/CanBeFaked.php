<?php

declare(strict_types=1);

namespace App\Services\Concerns;

use Illuminate\Support\Facades\Http;

trait CanBeFaked
{
    /**
     * Proxy Fake request call through to Http::fake()
     *
     * @param null|callable|array $callback
     * @return void
     */
    public static function fake(null|callable|array $callback = null): void
    {
        Http::fake(
            callback: $callback,
        );
    }
}
