<?php

declare(strict_types=1);

it('can test', function () {
    $result = app('db')->selectOne('SELECT 1 as val');

    expect($result)
        ->toBeObject()
        ->val->toEqual(1);
});
