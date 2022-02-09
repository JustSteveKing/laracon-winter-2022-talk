<?php

declare(strict_types=1);

namespace App\Services\GitHub\Factories;

use App\Services\GitHub\DataObjects\Owner;
use JustSteveKing\LaravelToolkit\Contracts\FactoryContract;

class OwnerFactory implements FactoryContract
{
    public static function make(array $attributes): Owner
    {
        return new Owner(
            id:     intval(data_get($attributes, 'id')),
            login:  strval(data_get($attributes, 'login')),
            type:   strval(data_get($attributes, 'type')),
            avatar: strval(data_get($attributes, 'avatar_url')),
            uri:    strval(data_get($attributes, 'html_url')),
        );
    }
}
