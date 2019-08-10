<?php


namespace ck_framework\Router;

use ck_framework\Utils\SnippetUtils;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\Route as ZendRoute;

class Router
{
    /**
     * @var FastRouteRouter
     */
    private $router;
    /**
     * @var array
     */
    private $RouteList;


    public function __construct()
    {
        $this->router = new FastRouteRouter();
    }

    /**
     * @param string $path
     * @param string|callable $callable
     * @param string $name
     */
    public function get(string $path, $callable, string $name)
    {
        $methods = 'GET';
        $this->router->addRoute( new ZendRoute($path, new MiddlewareApp($callable), [$methods], $name));

        $params = $this->ParseParams($path);
        $this->RouteList[] = ['path' => $path, 'middleware' => $callable, 'methods' => $methods, 'name' => $name, 'params' => $params];
    }

    /**
     * @param string $path
     * @param string|callable $callable
     * @param string $name
     */
    public function post(string $path, $callable, string $name)
    {
        $methods = 'POST';
        $this->router->addRoute(new ZendRoute($path, new MiddlewareApp($callable), [$methods], $name));

        $params = $this->ParseParams($path);
        $this->RouteList[] = ['path' => $path, 'middleware' => $callable, 'methods' => $methods, 'name' => $name, 'params' => $params];
    }

    /**
     * @param ServerRequestInterface $request
     * @return Route|null
     */
    public function match(ServerRequestInterface $request): ?Route
    {
        $result = $this->router->match($request);
        if ($result->isSuccess()) {

            return new Route(
                $result->getMatchedRouteName(),
                $result->getMatchedRoute()->getMiddleware(),
                $result->getMatchedParams()
            );
        }
        return null;
    }

    public function generateUri(string $name, array $params = [], array $queryParams = [], bool $wire = true): ?string
    {
        if ($wire){
            $uri = $this->router->generateUri($name, $params);
            if (!empty($queryParams)) {
                return $uri . '?' . http_build_query($queryParams);
            }
        }else{
            return $name . '?' . http_build_query($queryParams);
        }
        return $uri;
    }

    /**
     * Return redirect response
     *
     * @param string $path
     * @param array $params
     * @param array $queryParams
     * @return ResponseInterface
     */
    public function redirect(string $path, array $params = [], array $queryParams = []): ResponseInterface
    {
        $redirectUri = $this->generateUri($path, $params, $queryParams);
        return (new Response())
            ->withStatus(301)
            ->withHeader('location', $redirectUri);
    }

    /**
     * @return array
     */
    public function getRouteList()
    {
        return $this->RouteList;
    }

    public function ParseParams(string $path){
        $params = false;
        if (SnippetUtils::IfStringContains('{', $path)){
            $uri = explode('/', $path);
            $params_uri = [];
            foreach ($uri as $element){
                if (SnippetUtils::IfStringContains('{', $element)){
                    $element = explode('}', $element);
                    $params_uri = $element;
                }
            }

            foreach ($params_uri as $element){
                if ($element != ""){
                    $element = $element . '}';
                    $parse = SnippetUtils::GetStringBetween($element,'{', '}');
                    $ArgsRegex =  SnippetUtils::GetTextAfterChar(':', $parse);
                    $ArgsRegex = str_replace('+', '' , $ArgsRegex);
                    $ArgsName =  SnippetUtils::GetTextBeforeChar(':', $parse);

                    $params[$ArgsName] = $ArgsRegex;
                };
            }
        }

        return $params;
    }
}