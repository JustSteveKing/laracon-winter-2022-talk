<?php

declare(strict_types=1);

namespace App\Services\GitHub\Resources;

use App\Services\GitHub\Exceptions\GitHubRequestException;
use App\Services\GitHub\Factories\ReleaseFactory;
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
        $request = $this->service->makeRequest();

        $response = $request->get(
            url: "/repos/{$owner}/{$repo}/releases"
        );

        if ($response->failed()) {
            throw new GitHubRequestException(
                response: $response,
            );
        }

        return $response->collect()->map(fn(array $repo) => ReleaseFactory::make(
            attributes: $repo,
        ));
    }

    public function latest(string $owner, string $repo): DataObjectContract
    {
        $request = $this->service->makeRequest();

        $response = $request->get(
            url: "/repos/{$owner}/{$repo}/releases/latest"
        );

        if ($response->failed()) {
            throw new GitHubRequestException(
                response: $response,
            );
        }

        return ReleaseFactory::make(
            attributes: $response->json(),
        );
    }

    public function version(string $owner, string $repo, string $version): DataObjectContract
    {
        $request = $this->service->makeRequest();

        $response = $request->get(
            url: "/repos/{$owner}/{$repo}/releases/tags/{$version}"
        );

        if ($response->failed()) {
            throw new GitHubRequestException(
                response: $response,
            );
        }

        return ReleaseFactory::make(
            attributes: $response->json(),
        );
    }
}
