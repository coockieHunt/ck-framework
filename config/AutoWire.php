<?php

use ck_framework\Renderer\RendererInterface;
use ck_framework\Renderer\TwigRendererFactory;
use ck_framework\Router\Route;
use function DI\autowire;
use function DI\factory;
use Psr\Container\ContainerInterface;

class AutoWire
{
    /**
     * @var array
     */
    private $AutoWire;

    /**
     * build autowire config
     * AutoWire constructor.
     */
    public function __construct()
    {
        $this->AutoWire = [
            RendererInterface::class => factory(TwigRendererFactory::class),
            Route::class => autowire(),
            PDO::class => function (ContainerInterface $c){
                return new pdo(
                    'mysql:host=' . $c->get('database.host') . ';dbname=' . $c->get('database.name'),
                    $c->get('database.user'),
                    $c->get('database.pass'),
                    [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]
                );
            }
        ];
    }

    /**
     * get autowire php-di
     * @return array
     */
    public function getAutoWire(): array
    {
        return $this->AutoWire;
    }
}