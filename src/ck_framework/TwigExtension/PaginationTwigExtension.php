<?php


namespace ck_framework\Twig;


use ck_framework\Pagination\Pagination;
use ck_framework\Pagination\RendererPagination;
use ck_framework\Router\Router;
use Exception;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PaginationTwigExtension extends AbstractExtension
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
            new TwigFunction('Pagination', [$this, 'AddPagination']),
        ];
    }

    /**
     * add pagination on view
     * @param $template
     * @param Pagination $pagination
     * @throws Exception
     */
    public function AddPagination($template, Pagination $pagination): void
    {
        $RenderPagination = new RendererPagination();
        $Render = $RenderPagination->Renderer($template, $pagination, $this->router);
        echo implode("\n", $Render);
    }
}