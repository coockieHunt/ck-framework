<?php


namespace ck_framework\Renderer;


use Psr\Container\ContainerInterface;
use Twig\Error\LoaderError;

class TwigRendererFactory
{
    /**
     * @param ContainerInterface $container
     * @return TwigRenderer
     * @throws LoaderError
     */
    public function __invoke(ContainerInterface $container)
    {
        return new TwigRenderer(dirname(__DIR__) . $container->get('view.patch'), $container);
    }
}