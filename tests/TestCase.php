<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

class TestCase extends PHPUnit\Framework\TestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setUp(): void
    {
        $this->container = $this->buildContainer();
    }

    protected function buildContainer($definitions = []): ContainerInterface
    {
        $builder = new ContainerBuilder();
        // $builder->addDefinitions($definitions);

        return $builder->build();
    }
}
