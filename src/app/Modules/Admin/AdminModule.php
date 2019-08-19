<?php


namespace app\Modules\Admin;


use app\ModuleFunction;
use app\Modules\Admin\Actions\AdminActions;
use app\Modules\Admin\Actions\AdminCategoryActions;
use app\Modules\Admin\Actions\AdminPostActions;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Psr\Container\ContainerInterface;
use Exception;

class AdminModule
{
    CONST DEFINITIONS = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
    /**
     * @var ModuleFunction
     */
    private $moduleFunction;

    /**
     * AdminModule constructor.
     * @param ModuleFunction $moduleFunction
     * @throws Exception
     */
    public function __construct(ModuleFunction $moduleFunction){
        $this->moduleFunction = $moduleFunction;

        $this->moduleFunction->init(__DIR__, $this);
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
        $this->moduleFunction->AddRoute(
            '/',
            [AdminActions::class, 'index'],
            'admin.index'
        );

        /***
         * ROUTER ROUTE
         */
        $this->moduleFunction->AddRoute(
            '/route',
            [AdminActions::class, 'routing'],
            'admin.routing'
        );

        $this->moduleFunction->AddRoute(
            '/route/build/{name:[a-z\-]+}',
            [AdminActions::class, 'routingBuild'],
            'admin.routing.build',
            ['GET','POST']
        );


        /***
         * POSTS CATEGORY
         */
        $this->moduleFunction->AddRoute(
            '/category',
            [AdminCategoryActions::class, 'category'],
            'admin.category'
        );

        $this->moduleFunction->AddRoute(
            '/category/new',
            [AdminCategoryActions::class, 'categoryNew'],
            'admin.category.new',
            ['GET','POST']
        );

        $this->moduleFunction->AddRoute(
            '/category/delete/{id:[0-9]+}',
            [AdminCategoryActions::class, 'categoryDelete'],
            'admin.category.delete',
            ['GET','POST']
        );

        $this->moduleFunction->AddRoute(
            '/category/edit/{id:[0-9]+}',
            [AdminCategoryActions::class, 'categoryEdit'],
            'admin.category.edit',
            ['GET','POST']
        );
        /***
         * POST ROUTE
         */
        $this->moduleFunction->AddRoute(
            '/post',
            [AdminPostActions::class, 'posts'],
            'admin.posts'
        );

        $this->moduleFunction->AddRoute(
            '/post/new',
            [AdminPostActions::class, 'postNew'],
            'admin.posts.new',
            ['GET','POST']
        );

        $this->moduleFunction->AddRoute(
            '/post/edit/{id:[0-9]+}',
            [AdminPostActions::class, 'postEdit'],
            'admin.posts.edit',
            ['GET','POST']
        );

        $this->moduleFunction->AddRoute(
            '/post/delete/{id:[0-9]+}',
            [AdminPostActions::class, 'postDelete'],
            'admin.posts.delete'
        );
    }
}