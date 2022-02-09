<?php

declare(strict_types=1);

namespace App\Services\GitHub\Resources;

use App\Services\GitHub\Requests\CreateRepository;
use Illuminate\Support\Collection;
use JustSteveKing\LaravelToolkit\Contracts\ResourceContract;
use JustSteveKing\LaravelToolkit\Contracts\ServiceContract;
use JustSteveKing\LaravelToolkit\Contracts\DataObjectContract;

class RepositoryResource implements ResourceContract
{
    public function __construct(
        private readonly ServiceContract $service,
    ) {}

    public function service(): ServiceContract
    {
        return $this->service;
    }

    public function organisation(string $organisation): Collection
    {
        // "/orgs/{$organisation}/repos"
    }

    public function user(string $owner, string $repository): DataObjectContract
    {
        // "/repos/{$owner}/{$repository}"
    }

    public function create(
        string $owner,
        CreateRepository $requestBody,
        bool $organisation = false,
    ): DataObjectContract {
        // $organisation ? "/orgs/{$owner}/repos" : "/user/repos",
    }
}
