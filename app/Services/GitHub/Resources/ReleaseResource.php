<?php

declare(strict_types=1);

namespace App\Services\GitHub\Resources;

use Illuminate\Support\Collection;
use JustSteveKing\LaravelToolkit\Contracts\ResourceContract;
use JustSteveKing\LaravelToolkit\Contracts\ServiceContract;
use JustSteveKing\LaravelToolkit\Contracts\DataObjectContract;

class ReleaseResource implements ResourceContract
{
    public function __construct(
        private readonly ServiceContract $service,
    ) {}

    public function service(): ServiceContract
    {
        return $this->service;
    }

    public function list(string $owner, string $repo): Collection
    {
        // "/repos/{$owner}/{$repo}/releases"
    }

    public function latest(string $owner, string $repo): DataObjectContract
    {
        // "/repos/{$owner}/{$repo}/releases/latest",
    }

    public function version(string $owner, string $repo, string $version): DataObjectContract
    {
        // "/repos/{$owner}/{$repo}/releases/tags/{$version}"
    }
}
