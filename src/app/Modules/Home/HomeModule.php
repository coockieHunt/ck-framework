<?php


namespace app\Modules\Home;


use app\ModuleFunction;
use app\Modules\Home\Actions\HomeActions;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Exception;
use PhpParser\Node\Expr\AssignOp\Mod;
use Psr\Container\ContainerInterface;

class HomeModule
{
    CONST DEFINITIONS = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
    /**
     * @var ModuleFunction
     */
    private $moduleFunction;

    /**
     * BlogModule constructor.
     * @param ModuleFunction $moduleFunction
     * @throws Exception
     */
    public function __construct(ModuleFunction $moduleFunction){
        $this->moduleFunction = $moduleFunction;

        $this->moduleFunction->init(__DIR__, $this);
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
        $this->moduleFunction->AddRoute(
            '/',
            [HomeActions::class, 'index'],
            'home.index'
        );
    }
}