<?php

namespace ck_framework;

use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class App
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var array
     */
    private $modules = [];

    /**
     * App constructor.
     * @param ContainerInterface $container
     * @param string[] $modules
     */
    public function __construct(ContainerInterface $container, array $modules = [])
    {
        $this->container = $container;
        foreach ($modules as $module) {
            $module = $container->get($module);
            $module->ListRoute();
            $this->modules[] = $module;
        }
    }

    /**
     * Run App process
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        //get all necessary var
        $uri = $request->getUri()->getPath();
        $router = $this->container->get(Router::class);
        $homeUri = $this->container->get('home.route');

        //redirect uri at home route
        if (empty($uri) || $uri === "/"){
            $uri = $router->generateUri($homeUri);
            return (new Response())
                ->withStatus(301)
                ->withHeader('Location', $uri);
        }

        //if uri end / redirect uri - /
        if(!empty($uri) && $uri[-1] === "/") {
            return (new Response())
                ->withStatus(301)
                ->withHeader('Location', substr($uri, 0, -1));
        }

        //check if current uri is match route register
        $route = $router->match($request);

        //if route not matched
        if ($route === null) {return $this->Return404();}

        //get params uri
        $params = $route->getParams();
        $request = array_reduce(array_keys($params), function ($request, $key) use ($params) {
            return $request->withAttribute($key, $params[$key]);
        }, $request);
        $Middleware = $route->getCallback();
        $callback = $Middleware->getCallback();
        if (is_string($callback)) {
            $callback = $this->container->get($callback);
        }

        //return response uri
        $response = call_user_func_array($callback, [$request]);
        if (is_string($response)) {
            return new Response(200, [], $response);
        } elseif ($response instanceof ResponseInterface) {
            return $response;
        } else {
            throw new Exception('The response is not a string or an instance of ResponseInterface');
        }
    }

    /**
     * return 404 response
     * @return Response
     */
    private function Return404()
    {
        $container = $this->container;
        $Renderer = $container->get(RendererInterface::class);
        $status = 404;
        $headers = [];
        $body = $Renderer->Render("@error/404NotFound");
        $response = new Response($status, $headers, $body);

        return $response;
    }



    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}