<?php


namespace app\Modules\Admin;


use app\ModuleFunction;
use app\Modules\Admin\Actions\AdminActions;
use app\Modules\Admin\Actions\AdminPostActions;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Psr\Container\ContainerInterface;
use Exception;

class AdminModule extends ModuleFunction
{
    CONST DEFINITIONS = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
    CONST MIGRATIONS = __DIR__ . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations';
    CONST SEEDS =  __DIR__ . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'seeds';


    public function __construct(Router $router, RendererInterface  $renderer, ContainerInterface $container, PostsTable $postsTable){
        parent::init($router, $renderer, $container, __DIR__);
    }

    /**
     * List route for this module
     * example :
     *     $this->AddRoute(
     *          '/world', {uri}
     *          'index', {function name}
     *          'blog.index' {route name}
     *          'POST' (method request !GET default
     *          'true' [use module prefix !true default}
     *      );
     *
     * @return void
     * @throws Exception
     */
    public function ListRoute(): void {

        /***
         * MAIN ROUTE
         */
        $this->AddRoute(
            '/',
            [AdminActions::class, 'index'],
            'admin.index'
        );

        /***
         * ROUTER ROUTE
         */
        $this->AddRoute(
            '/route',
            [AdminActions::class, 'routing'],
            'admin.routing'
        );

        /***
         * POST ROUTE
         */
        $this->AddRoute(
            '/post',
            [AdminPostActions::class, 'posts'],
            'admin.posts'
        );

        $this->AddRoute(
            '/post/edit/{id:[0-9]+}',
            [AdminPostActions::class, 'postEdit'],
            'admin.posts.edit'
        );

        $this->AddRoute(
            '/post/new',
            [AdminPostActions::class, 'postNew'],
            'admin.posts.new'
        );

        $this->AddRoute(
            '/post/new',
            [AdminPostActions::class, 'postNew'],
            'admin.posts.new.post',
            'POST'
        );

        $this->AddRoute(
            '/post/edit/{id:[0-9]+}',
            [AdminPostActions::class, 'postEdit'],
            'admin.posts.edit.post',
            'POST'
        );

        $this->AddRoute(
            '/post/delete/{id:[0-9]+}',
            [AdminPostActions::class, 'postDelete'],
            'admin.posts.delete'
        );

        $this->AddRoute(
            '/new',
            [AdminActions::class, 'newDash'],
            'admin.template.new'
        );
    }
}