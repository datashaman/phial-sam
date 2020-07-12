<?php

define('LAMBDA_TASK_API', getenv('AWS_LAMBDA_RUNTIME_API'));
define('LAMBDA_TASK_HANDLER', getenv('_HANDLER'));
define('LAMBDA_TASK_ROOT', getenv('LAMBDA_TASK_ROOT'));

require_once LAMBDA_TASK_ROOT . '/vendor/autoload.php';

use DI\ContainerBuilder;
use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;

class RuntimeHandler
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $requestId = '';

    public function __construct()
    {
        $this->createClient();

        try {
            $this->configureErrorReporting();
            $this->buildContainer();
        } catch (Throwable $exception) {
            $this->error(
                'Error initializing handler',
                [
                    'message' => $exception->getMessage(),
                ]
            );
            $this->postError($exception);
        }
}

    public function __invoke(): void
    {
        $this->info('Invoke handler event loop');

        while (true) {
            try {
                $event = $this->getNextInvocation();
                $context = [];
                $response = $this->container->call(
                    LAMBDA_TASK_HANDLER,
                    [
                        'event' => $event,
                    ]
                );
                $this->postResponse($response);
            } catch (Throwable $exception) {
                $this->error(
                    'Error processing event',
                    [
                        'event' => $event ?? [],
                        'response' => $response ?? '',
                    ]
                );
                $this->postError($exception);
            }
        }
    }

    private function configureErrorReporting(): void
    {
        $this->info('Configure error reporting');

        error_reporting(
            E_ALL &
            ~E_USER_DEPRECATED &
            ~E_DEPRECATED &
            ~E_STRICT &
            ~E_NOTICE
        );
    }

    private function buildContainer(): void
    {
        $this->info('Build container');

        $containerBuilder = new ContainerBuilder();

        if ($configPath = $this->taskPath('config.php')) {
            $containerBuilder->addDefinitions($configPath);
        }

        $this->container = $containerBuilder->build();
    }

    private function createClient(): void
    {
        $this->info('Create client');

        $this->client = new Client(
            [
                'base_uri' => sprintf(
                    'http://%s/2018-06-01/',
                    LAMBDA_TASK_API
                ),
            ]
        );
    }

    private function getNextInvocation(): array
    {
        $this->info('Get next invocation');

        $response = $this->client->get('runtime/invocation/next');
        $this->requestId = $response->getHeader('lambda-runtime-aws-request-id')[0];

        return json_decode($response->getBody(), true);
    }

    private function postResponse(array $response): void
    {
        $this->info('Post response');

        $this->client->post(
            "runtime/invocation/{$this->requestId}/response",
            [
                'json' => $response,
            ]
        );
    }

    private function postError(Throwable $exception): void
    {
        if ($this->client) {
            $this->info('Post error');

            $path = $this->requestId
                ? "runtime/invocation/{$this->requestId}/error"
                : 'runtime/init/error';

            $error = [
                'errorMessage' => sprintf(
                    '%s %s:%d',
                    $exception->getMessage(),
                    $exception->getFile(),
                    $exception->getLine()
                ),
                'errorType' => get_class($exception),
            ];

            $this->client->post(
                $path,
                [
                    'json' => $error,
                    'headers' => [
                        'Lambda-Runtime-Function-Error-Type' => 'Unhandled',
                    ],
                ]
            );
        }

        if (!$this->requestId) {
            exit(1);
        }
    }

    private function taskPath(string $path = '')
    {
        return realpath(LAMBDA_TASK_ROOT . '/' . $path);
    }

    private function fwrite($handle, string $message, array $context = []): void
    {
        fwrite(
            $handle,
            sprintf(
                '%s%s%s',
                $message,
                $context ? (' ' . json_encode($context)) : '',
                PHP_EOL
            )
        );
    }

    private function error(string $message, array $context = []): void
    {
        $this->fwrite(STDERR, $message, $context);
    }

    private function info(string $message, array $context = []): void
    {
        $this->fwrite(STDOUT, $message, $context);
    }
}

(new RuntimeHandler())();
