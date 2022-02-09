<?php

declare(strict_types=1);

namespace App\Services\GitHub\Exceptions;

use Illuminate\Http\Client\RequestException;

class GitHubRequestException extends RequestException {}
