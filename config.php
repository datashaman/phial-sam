<?php

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    LoggerInterface::class => function (ContainerInterface $container) {
        $logger = new Logger('phial-sam');
        $formatter = new LineFormatter("%channel%.%level_name%: %message% %context% %extra%\n", null, false, true);
        $handler = new StreamHandler('php://stderr', Logger::DEBUG);

        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);

        return $logger;
    },
];
