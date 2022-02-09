<?php

declare(strict_types=1);

namespace App\Services\GitHub\Factories;

use App\Services\GitHub\DataObjects\Release;
use Carbon\Carbon;
use JustSteveKing\LaravelToolkit\Contracts\FactoryContract;

class ReleaseFactory implements FactoryContract
{
    public static function make(array $attributes): Release
    {
        return new Release(
            id:          intval(data_get($attributes, 'id')),
            name:        strval(data_get($attributes, 'name')),
            tag:         strval(data_get($attributes, 'tag_name')),
            url:         strval(data_get($attributes, 'html_url')),
            description: strval(data_get($attributes, 'body')),
            draft:       boolval(data_get($attributes, 'draft')),
            author:      OwnerFactory::make(
                attributes: (array) data_get($attributes, 'author'),
            ),
            created:     Carbon::parse(
                time: strval(data_get($attributes, 'created_at')),
            ),
        );
    }
}
