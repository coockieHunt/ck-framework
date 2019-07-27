<?php


namespace app\Modules\Home\Actions;


use app\ModuleFunction;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Psr\Container\ContainerInterface;

class HomeActions extends ModuleFunction
{
    public function __construct(Router $router, RendererInterface  $renderer, ContainerInterface $container){
        $dir = substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR));
        parent::init($router, $renderer, $container,  $dir);
    }

    public function index(): string
    {
        return $this->Render("index");
    }
}