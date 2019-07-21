<?php


namespace app;


use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Exception;
use Psr\Container\ContainerInterface;

class ModuleFunction
{
    CONST DEFINITIONS = NULL;

    /**
     * @var Router
     */
    protected $router;
    /**
     * @var RendererInterface
     */
    static $renderer;
    /**
     * @var ContainerInterface
     */
    protected $container;


    /**
     * @param Router $router
     * @param RendererInterface $renderer
     * @param ContainerInterface $container
     * @param string $directory
     * @throws Exception
     */
    protected function init(Router $router, RendererInterface $renderer, ContainerInterface $container, string $directory){
        $this->router = $router;
        self::$renderer = $renderer;
        $this->container = $container;

        $this->AddViewFolder($directory);

    }

    /**
     * Add route add prefix if use_prefix is true (default)
     * @param string $uri
     * @param string $function
     * @param string $name
     * @param bool $use_prefix
     */
    protected function AddRoute(string $uri, string $function, string $name, bool $use_prefix = true){
        $namespace = explode('\\', get_class($this));
        $prefix = null;

        if ($use_prefix){
            $key =  strtolower($namespace[2] . '.prefix');
            if ($this->container->has($key)){
                $prefix = "/" . $this->container->get($key) ;
            }
        }

        $this->router->get(
            $prefix . $uri,
            [get_class($this), $function],
            $name
        );
    }

    /**
     * add render route with for module
     * @param string $view
     * @param array|null $params
     * @return mixed
     */
    static function Render(string $view, ?array $params = [])
    {
        if (is_null($params)) {
            return  self::$renderer->Render("@" . static::GetClassName() . "/" . $view);
        } else
            return self::$renderer->Render("@" . static::GetClassName() . "/" . $view, $params);
    }

    /**
     * add view folder
     * @param string $directory
     * @return void
     * @throws Exception
     */
    private function AddViewFolder(string $directory){
        $path = $directory . DIRECTORY_SEPARATOR . 'views';
        if (file_exists ( $path )){
            self::getRenderer()->addPath(
                $path,
                $this->GetClassName()
            );
        }else{
            throw new Exception("View folder do not exist in : " . $this->GetClassName());
        }

    }

    /**
     * get child name function
     * @return string
     */
    static function GetClassName() : string
    {
        $namespace = explode('\\', get_class());
        return end($namespace);
    }


    /**
     * get renderer
     * @return RendererInterface
     */
    static function getRenderer(): RendererInterface
    {
        return static::$renderer;
    }
}