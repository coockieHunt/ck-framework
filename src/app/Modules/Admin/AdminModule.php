<?php


namespace app\Modules\Admin;


use app\ModuleFunction;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Psr\Container\ContainerInterface;

class AdminModule extends ModuleFunction
{
    CONST DEFINITIONS = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
    /**
     * @var PostsTable
     */
    private $postsTable;

    public function __construct(Router $router, RendererInterface  $renderer, ContainerInterface $container, PostsTable $postsTable){
        parent::init($router, $renderer, $container, __DIR__);
        $this->postsTable = $postsTable;
    }

    /**
     * List route for this module
     * example :
     *     $this->AddRoute(
     *          '/world', {uri}
     *          'index', {function name}
     *          'blog.index' {route name}
     *          'true' [use module prefix !true default}
     *      );
     *
     * @return void
     */
    public function ListRoute(): void {
        $this->AddRoute(
            '/',
            [$this, 'index'],
            'admin.index'
        );

        $this->AddRoute(
            '/routing',
            [$this, 'routing'],
            'admin.routing'
        );

        $this->AddRoute(
            '/posts',
            [$this, 'posts'],
            'admin.posts'
        );
    }

    public function index(){
       return $this->Render('index');
    }

    public function routing(){
        $list = $this->router->getRouteList();

        return $this->Render('routing',
            [
                'routing' => $list
            ]
        );
    }

    public function posts(){
        $postsList = $this->postsTable->FindAll();

        return $this->Render('posts',
            [
                'posts' => $postsList
            ]
        );
    }
}