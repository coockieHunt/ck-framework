<?php


namespace app\Modules\Home;


use app\ModuleFunction;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeModule extends ModuleFunction
{
    CONST DEFINITIONS = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';

    /**
     * BlogModule constructor.
     * @param Router $router
     * @param RendererInterface $renderer
     * @param ContainerInterface $container
     * @throws Exception
     */
    public function __construct(Router $router, RendererInterface  $renderer, ContainerInterface $container){
        parent::init($router, $renderer, $container, __DIR__);
    }

    /**
     * List route for this module
     * example :
     *     $this->AddRoute(
     *          '/world', {uri}
     *          'index', {function name}
     *          'blog.index' {route name}
     *          'true' [use module prefix !true default}
     *      );
     *
     * @return void
     */
    public function ListRoute(): void {
        $this->AddRoute(
            '',
            'index',
            'home'
        );
    }

    static function index(): string
    {
        return '<h1>Bienvenue sur le premiere pages</h1>';
    }
}