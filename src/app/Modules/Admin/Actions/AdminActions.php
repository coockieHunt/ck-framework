<?php


namespace app\Modules\Admin\Actions;


use app\ModuleFunction;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Exception;
use Psr\Container\ContainerInterface;

class AdminActions extends ModuleFunction
{
    /**
     * @var PostsTable
     */
    private $postsTable;

    /**
     * AdminActions constructor.
     * @param Router $router
     * @param RendererInterface $renderer
     * @param ContainerInterface $container
     * @param PostsTable $postsTable
     * @throws Exception
     */
    public function __construct(Router $router, RendererInterface  $renderer, ContainerInterface $container , PostsTable $postsTable)
    {
        $dir = substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR));
        parent::init($router, $renderer, $container,  $dir);
        $this->postsTable = $postsTable;
    }

    /**
     * dashboard view
     * @return mixed
     */
    public function index(){
        $count = [
            "route" => count($this->router->getRouteList()),
            "posts" => count($this->postsTable->FindAll()),
        ];

        return $this->Render('index',["count" => $count]);
    }

    /**
     * list all route
     * @return mixed
     */
    public function routing(){
        $list = $this->router->getRouteList();

        return $this->Render('routing\routing',
            [
                'routing' => $list
            ]
        );
    }
}