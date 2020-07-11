<?php

declare(strict_types=1);

use App\Handler;

class HandlerTest extends TestCase
{
    public function testHandler()
    {
        $event = [];

        $ret = $this->container->call(
            Handler::class,
            [
                'event' => $event,
            ]
        );

        $body = json_decode($ret['body'], true);

        $this->assertEquals(200, $ret['statusCode']);
        $this->assertEquals('hello world', $body['message']);
    }
}
