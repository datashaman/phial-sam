<?php

declare(strict_types=1);

namespace App;

class Handler
{
    function __invoke($event)
    {
        return [
            'statusCode' => 200,
            'body' => json_encode(
                [
                    'message' => 'hello world',
                ]
            )
        ];
    }
}
