<?php


namespace app\Modules\Blog;

use app\ModuleFunction;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Psr\Container\ContainerInterface;

class BlogModule extends ModuleFunction
{
    CONST DEFINITIONS = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';

    /**
     * @var Router
     */
    private $router;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var RendererInterface
     */
    private $renderer;

    public function __construct(Router $router, RendererInterface  $renderer, ContainerInterface $container){

        $this->router = $router;
        $this->container = $container;
        $this->renderer = $renderer;
    }

}