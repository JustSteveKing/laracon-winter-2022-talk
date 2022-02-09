<?php

declare(strict_types=1);

namespace App\Services\GitHub\Resources;

use App\Services\GitHub\DataObjects\Repository;
use App\Services\GitHub\Exceptions\GitHubRequestException;
use App\Services\GitHub\Factories\OwnerFactory;
use App\Services\GitHub\Factories\RepositoryFactory;
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
        $request = $this->service->makeRequest();

        $response = $request->get(
            url: "/orgs/{$organisation}/repos"
        );

        if ($response->failed()) {
            throw new GitHubRequestException(
                response: $response,
            );
        }

        return $response->collect()->map(fn(array $repo) => RepositoryFactory::make(
            attributes: $repo,
        ));
    }

    public function user(string $owner, string $repository): DataObjectContract
    {
        $request = $this->service->makeRequest();

        $response = $request->get(
            url: "/repos/{$owner}/{$repository}"
        );

        if ($response->failed()) {
            throw new GitHubRequestException(
                response: $response,
            );
        }

        return RepositoryFactory::make(
            attributes: $response->json(),
        );
    }

    public function create(
        string $owner,
        CreateRepository $requestBody,
        bool $organisation = false,
    ): DataObjectContract {
        $request = $this->service()->makeRequest();

        $response = $request->post(
            url: $organisation ? "/orgs/{$owner}/repos" : "/user/repos",
            data: $requestBody->toRequest(),
        );

        if ($response->failed()) {
            throw new GitHubRequestException(
                response: $response,
            );
        }

        return RepositoryFactory::make(
            attributes: (array) $response->json(),
        );
    }
}
