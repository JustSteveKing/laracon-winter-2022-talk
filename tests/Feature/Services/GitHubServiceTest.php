<?php

declare(strict_types=1);

use App\Services\GitHub\GitHubService;
use App\Services\GitHub\Requests\CreateRepository;
use App\Services\GitHub\Resources\ReleaseResource;
use App\Services\GitHub\Resources\RepositoryResource;
use App\Services\GitHub\DataObjects\Release;
use App\Services\GitHub\DataObjects\Repository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use JustSteveKing\StatusCode\Http as StatusCode;

it('can build a new GitHub Service', function (string $string) {
    expect(
        new GitHubService(
            baseUri: $string,
            key:     $string,
            timeout: 10,
        )
    )->toBeInstanceOf(GitHubService::class);
})->with('strings');

it('can create a Pending Request', function (string $string) {
    $service = new GitHubService(
        baseUri: $string,
        key:     $string,
        timeout: 10,
    );

    expect(
        $service->makeRequest(),
    )->toBeInstanceOf(PendingRequest::class);
})->with('strings');

it('can resolve a GitHub Service from the container', function () {
    expect(
        resolve(GitHubService::class)
    )->toBeInstanceOf(GitHubService::class);
});

it('can create a Pending Request from the Resolved Service', function () {
    expect(
        resolve(GitHubService::class)->makeRequest(),
    )->toBeInstanceOf(PendingRequest::class);
});

it('resolves as a singleton only', function () {
    $service = resolve(GitHubService::class);

    expect(
        resolve(GitHubService::class)
    )->toEqual($service);
});

it('can get a repository resource', function () {
    /**
     * @var GitHubService
     */
    $github = resolve(GitHubService::class);

    expect(
        $github->repositories(),
    )->toBeInstanceOf(RepositoryResource::class);
});

it('can get a release resource', function () {
    /**
     * @var GitHubService
     */
    $github = resolve(GitHubService::class);

    expect(
        $github->releases(),
    )->toBeInstanceOf(ReleaseResource::class);
});

it('can fetch organisation repos', function () {
    GitHubService::fake([
        'github.com/orgs/*/repos' => Http::response(
            body:   fixture('GitHub/OrganisationRepos'),
            status: StatusCode::OK,
        ),
    ]);

    /**
     * @var GitHubService
     */
    $github = resolve(GitHubService::class);
    $repos = $github->repositories()->organisation(
        organisation: 'laravel',
    );

    expect(
        $repos
    )->toBeInstanceOf(Collection::class);

    $repos->each(function (Repository $repository) {
        expect(
            $repository->organisation->login
        )->toBeString()->toEqual('laravel');
    });
});

it('can get a single repository', function () {
    GitHubService::fake([
        'github.com/repos/*/*' => Http::response(
            body:   fixture('GitHub/OwnerRepository'),
            status: StatusCode::OK,
        )
    ]);

    /**
     * @var GitHubService
     */
    $github = resolve(GitHubService::class);
    $repo = $github->repositories()->user(
        owner: 'JustSteveKing',
        repository: 'laravel-transporter',
    );

    expect(
        $repo
    )->toBeInstanceOf(Repository::class)->name->toEqual('JustSteveKing/laravel-transporter');
});

it('can create a new organisation repository', function () {
    GitHubService::fake([
        'github.com/orgs/*/repos' => Http::response(
            body:   fixture('GitHub/OrganisationRepo'),
            status: StatusCode::OK,
        )
    ]);

    /**
     * @var GitHubService
     */
    $github = resolve(GitHubService::class);

    $repo = $github->repositories()->create(
        owner: 'laravel',
        organisation: true,
        requestBody: new CreateRepository(
            name: 'laravel',
            private: true
        ),
    );

    expect(
        $repo
    )->toBeInstanceOf(Repository::class)->name->toEqual('laravel/laravel');
});

it('can create a new user repository', function () {
    GitHubService::fake([
        'github.com/user/repos' => Http::response(
            body:   fixture('GitHub/OwnerRepository'),
            status: StatusCode::OK,
        )
    ]);

    /**
     * @var GitHubService
     */
    $github = resolve(GitHubService::class);

    $repo = $github->repositories()->create(
        owner: 'JustSteveKing',
        organisation: false,
        requestBody: new CreateRepository(
            name: 'laravel-transporter',
            private: true
        ),
    );

    expect(
        $repo
    )->toBeInstanceOf(Repository::class)->name->toEqual('JustSteveKing/laravel-transporter');
});

it('can get a list of releases', function () {
    GitHubService::fake([
        'github.com/repos/*/*/releases' => Http::response(
            body:   fixture('GitHub/RepoReleases'),
            status: StatusCode::OK,
        ),
    ]);

    /**
     * @var GitHubService
     */
    $github = resolve(GitHubService::class);
    $releases = $github->releases()->list(
        owner: 'JustSteveKing',
        repo: 'laravel-transporter'
    );

    expect(
        $releases
    )->toBeInstanceOf(Collection::class);

    $releases->each(function (Release $release) {
        expect(
            $release->author->login
        )->toBeString()->toEqual('JustSteveKing');
    });
});

it('can get the latest release', function () {
    GitHubService::fake([
        'github.com/repos/*/*/releases/latest' => Http::response(
            body:   fixture('GitHub/LatestRelease'),
            status: StatusCode::OK,
        ),
    ]);

    /**
     * @var GitHubService
     */
    $github = resolve(GitHubService::class);
    $release = $github->releases()->latest(
        owner: 'JustSteveKing',
        repo: 'laravel-transporter'
    );

    expect(
        $release
    )->toBeInstanceOf(Release::class);

    expect(
        $release->author->login
    )->toBeString()->toEqual('JustSteveKing');
});

it('can get a tagged release', function () {
    GitHubService::fake([
        'github.com/repos/*/*/releases/*' => Http::response(
            body:   fixture('GitHub/VersionRelease'),
            status: StatusCode::OK,
        ),
    ]);

    /**
     * @var GitHubService
     */
    $github = resolve(GitHubService::class);
    $release = $github->releases()->version(
        owner: 'JustSteveKing',
        repo: 'laravel-transporter',
        version: '0.9.5'
    );

    expect(
        $release
    )->toBeInstanceOf(Release::class);

    expect(
        $release->tag
    )->toBeString()->toEqual('0.9.5');
});

it('can create a new repository resource manually', function () {
    expect(
        new RepositoryResource(
            service: resolve(GitHubService::class),
        ),
    )->toBeInstanceOf(RepositoryResource::class);
});

it('can create a new release resource manually', function () {
    expect(
        new ReleaseResource(
            service: resolve(GitHubService::class),
        ),
    )->toBeInstanceOf(ReleaseResource::class);
});
