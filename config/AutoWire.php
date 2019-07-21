<?php

use ck_framework\Renderer\RendererInterface;
use ck_framework\Renderer\TwigRendererFactory;
use ck_framework\Router\Route;
use function DI\autowire;
use function DI\factory;

class AutoWire
{
    /**
     * @var array
     */
    private $AutoWire;

    public function __construct()
    {
        $this->AutoWire = [
            RendererInterface::class => factory(TwigRendererFactory::class),
            Route::class => autowire(),
        ];
    }

    /**
     * @return array
     */
    public function getAutoWire(): array
    {
        return $this->AutoWire;
    }
}