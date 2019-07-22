<?php


namespace app\Modules\Admin;


use app\ModuleFunction;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Psr\Container\ContainerInterface;

class AdminModule extends ModuleFunction
{
    CONST DEFINITIONS = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';

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
            '/',
            [$this, 'index'],
            'admin.index'
        );
    }

    public function index(){
       return $this->Render('index');
    }
}