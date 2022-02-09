<?php

declare(strict_types=1);

namespace App\Services\GitHub;

use App\Services\Concerns\CanBeFaked;
use Illuminate\Http\Client\PendingRequest;
use JustSteveKing\LaravelToolkit\Contracts\ResourceContract;
use JustSteveKing\LaravelToolkit\Contracts\ServiceContract;

class GitHubService implements ServiceContract
{
    use CanBeFaked;

    public function __construct(
        public readonly string $baseUri,
        public readonly string $key,
        public readonly int $timeout,
        public readonly null|int $retryTimes = null,
        public readonly null|int $retrySleep = null,
    ) {}

    public function makeRequest(): PendingRequest
    {
        //
    }

    public function repositories(): ResourceContract
    {
        //
    }

    public function releases(): ResourceContract
    {
        //
    }
}
