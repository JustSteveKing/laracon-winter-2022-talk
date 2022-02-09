<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\GitHub\GitHubService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(
            abstract: GitHubService::class,
            concrete: fn() => new GitHubService(
                baseUri:    strval(config('services.github.uri')),
                key:        strval(config('services.github.key')),
                timeout:    intval(config('services.github.timeout')),
                retryTimes: intval(config('services.github.retry.times')),
                retrySleep: intval(config('services.github.retry.sleep')),
            ),
        );
    }
}
