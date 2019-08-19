<?php


namespace app;


use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Psr\Container\ContainerInterface;
use Exception;
use stdClass;

class ModuleFunction
{
    CONST DEFINITIONS = NULL;
    CONST MIGRATIONS = NULL;
    CONST SEEDS = NULL;

    /**
     * @var Router
     */
    private $router;
    /**
     * @var RendererInterface
     */
    private $renderer;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var stdClass
     */
    private $className;

    public function __construct(Router $router, ContainerInterface $container, RendererInterface $renderer)
    {
        $this->router = $router;
        $this->renderer = $renderer;
        $this->container = $container;
    }

    /**
     * @param string $directory
     * @param $class
     * @throws Exception
     */
    public function init(string $directory, $class){
        $this->className = $class;
        $this->AddViewFolder($directory);
    }

    /**
     * add render route with for module
     * @param string $view
     * @param array|null $params
     * @return mixed
     */
    public function Render(string $view, ?array $params = [])
    {
        if (is_null($params)) {
            return $this->renderer->Render("@" . $this->GetClassName() . "/" . $view);
        } else
            return $this->renderer->Render("@" .  $this->GetClassName() . "/" . $view, $params);
    }

    /**
     * add view folder
     * @param string $directory
     * @return void
     * @throws Exception
     */
    private function AddViewFolder(string $directory){
        $path = $directory . DIRECTORY_SEPARATOR . 'Views';
        if (file_exists( $path)){
            $this->getRenderer()->addPath(
                $path,
                $this->GetClassName()
            );
        }else{
            throw new Exception("View folder do not exist in : '" . $path . "'");
        }

    }

    /**
     * get child name function
     * @return string
     */
    private function GetClassName() : string
    {
        $namespace = explode('\\', get_class($this->className));
        return end($namespace);
    }

    /**
     * get renderer
     * @return RendererInterface
     */
    public function getRenderer(): RendererInterface
    {
        return $this->renderer;
    }

    public function AddRoute(string $uri, array $function, $name = null, array $method = ['GET'], bool $use_prefix = true){
        $namespace = explode('\\', get_class($this->className));
        $prefix = null;
        if ($uri == "/"){$uri = "";};
        if ($use_prefix){
            $key =  strtolower($namespace[2] . '.prefix');
            if ($this->container->has($key)){
                $prefix = "/" . $this->container->get($key) ;
            }
        }

        $class = $this->container->get($function[0]);
        $function = $function[1];

        foreach ($method as $element){
            switch ($element) {
                case 'GET':
                    $this->router->get(
                        $prefix . $uri,
                        [$class, $function],
                        $name
                    );
                    break;
                case 'POST':
                    $this->router->post(
                        $prefix . $uri,
                        [$class, $function],
                        $name . '.POST'
                    );
                    break;
                default:
                    throw new Exception('method not found');
                    break;
            }
        }
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }
}