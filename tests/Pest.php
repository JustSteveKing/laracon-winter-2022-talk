<?php

declare(strict_types=1);

use Tests\TestCase;

uses(TestCase::class)->in('Feature');


expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});


function fixture(string $name): array
{
    $file = file_get_contents(
        filename: base_path("tests/Fixtures/$name.json"),
    );

    if(! $file) {
        throw new InvalidArgumentException(
            message: "Cannot find fixture: [$name] at tests/Fixtures/$name.json",
        );
    }

    return json_decode(
        json: $file,
        associative: true,
    );
}
