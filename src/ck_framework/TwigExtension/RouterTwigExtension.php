<?php


namespace ck_framework\TwigExtension;

use ck_framework\Router\Router;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;


class RouterTwigExtension extends AbstractExtension
{
    /**
     * @var router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction("generateUri", [$this, 'pathFor']),
        ];
    }

    public function pathFor(string $name, ?array $params = [], ?array $queryParams = [],  bool $wire = true)
    {
        return $this->router->generateUri($name, $params, $queryParams, $wire);
    }
}