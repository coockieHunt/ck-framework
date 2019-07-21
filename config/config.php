<?php

use ck_framework\Renderer\RendererInterface;
use ck_framework\Renderer\TwigRendererFactory;
use ck_framework\Router\Route;
use function DI\autowire;
use function DI\factory;

$space = DIRECTORY_SEPARATOR;


return [
    'view.patch' => $space . '..' . $space . 'app' . $space . 'Views',
    RendererInterface::class => factory(TwigRendererFactory::class),
    Route::class => autowire(),
];