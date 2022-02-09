<?php

declare(strict_types=1);

namespace App\Services\GitHub\DataObjects;

use JustSteveKing\LaravelToolkit\Contracts\DataObjectContract;

class Owner implements DataObjectContract
{
    public function __construct(
        public readonly int $id,
        public readonly string $login,
        public readonly string $type,
        public readonly string $avatar,
        public readonly string $uri,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'type' => $this->type,
            'avatar' => $this->avatar,
            'uri' => $this->uri,
        ];
    }
}
