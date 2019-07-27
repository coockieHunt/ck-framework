<?php


namespace app\Modules\Home;


use app\ModuleFunction;
use app\Modules\Home\Actions\HomeActions;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Exception;
use Psr\Container\ContainerInterface;

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
        parent::init($router, $renderer, $container,  __DIR__);
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
     * @throws Exception
     */
    public function ListRoute(): void {
        $this->AddRoute(
            '/',
            [HomeActions::class, 'index'],
            'home.index'
        );
    }
}