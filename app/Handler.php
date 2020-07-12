<?php

declare(strict_types=1);

namespace App;

class Handler
{
    function __invoke($event, $context = null)
    {
        $logger = $context->getLogger()->debug('Testing');

        return [
            'statusCode' => 200,
            'body' => json_encode(
                [
                    'message' => 'hello world',
                    'functionName' => $context->getFunctionName(),
                ]
            )
        ];
    }
}
