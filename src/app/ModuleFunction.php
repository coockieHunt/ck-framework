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
    protected $renderer;
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
        $this->renderer = $renderer;
        $this->container = $container;

        $this->AddViewFolder($directory);
    }

    /**
     * Add route add prefix if use_prefix is true (default)
     * @param string $uri
     * @param array $function
     * @param string $name
     * @param bool $use_prefix
     */
    protected function AddRoute(string $uri, array $function, string $name, bool $use_prefix = true){
        $namespace = explode('\\', get_class($this));
        $prefix = null;

        if ($uri == "/"){$uri = "";};
        if ($use_prefix){
            $key =  strtolower($namespace[2] . '.prefix');
            if ($this->container->has($key)){
                $prefix = "/" . $this->container->get($key) ;
            }
        }

        $this->router->get(
            $prefix . $uri,
            [$function[0], $function[1]],
            $name
        );
    }

    /**
     * add render route with for module
     * @param string $view
     * @param array|null $params
     * @return mixed
     */
    protected function Render(string $view, ?array $params = [])
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
        if (file_exists ( $path )){
            $this->getRenderer()->addPath(
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
    private function GetClassName() : string
    {
        $namespace = explode('\\', get_class($this));
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
}