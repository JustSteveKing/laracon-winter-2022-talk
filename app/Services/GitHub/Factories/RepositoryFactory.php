<?php

declare(strict_types=1);

namespace App\Services\GitHub\Factories;

use App\Services\GitHub\DataObjects\Repository;
use Carbon\Carbon;
use JustSteveKing\LaravelToolkit\Contracts\FactoryContract;

class RepositoryFactory implements FactoryContract
{
    public static function make(array $attributes): Repository
    {
        return new Repository(
            id:           intval(data_get($attributes, 'id')),
            name:         strval(data_get($attributes, 'full_name')),
            language:     strval(data_get($attributes, 'language')),
            private:      boolval(data_get($attributes, 'private')),
            fork:         boolval(data_get($attributes, 'fork')),
            archived:     boolval(data_get($attributes, 'archived')),
            uri:          strval(data_get($attributes, 'html_url')),
            organisation: OwnerFactory::make(
                attributes: (array) data_get($attributes, 'owner'),
            ),
            created:      Carbon::parse(
                time: strval(data_get($attributes, 'created_at')),
            ),
            description:  strval(data_get($attributes, 'description')),
        );
    }
}
