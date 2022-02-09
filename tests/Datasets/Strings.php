<?php

declare(strict_types=1);

use Illuminate\Support\Str;

dataset('strings', function () {
    yield Str::random();
    yield Str::random();
    yield Str::random();
    yield Str::random();
    yield Str::random();
});
