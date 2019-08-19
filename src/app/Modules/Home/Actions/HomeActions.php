<?php


namespace app\Modules\Home\Actions;


use app\ModuleFunction;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Exception;
use Psr\Container\ContainerInterface;

class HomeActions
{
    /**
     * @var ModuleFunction
     */
    private $moduleFunction;

    /**
     * HomeActions constructor.
     * @param ModuleFunction $moduleFunction
     * @throws Exception
     */
    public function __construct(ModuleFunction $moduleFunction){
        $dir = substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR));
        $this->moduleFunction = $moduleFunction;

        $this->moduleFunction->init($dir, $this);
    }

    public function index(): string
    {
        return $this->moduleFunction->Render("index");
    }
}