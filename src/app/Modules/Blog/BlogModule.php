<?php


namespace app\Modules\Blog;
use app\ModuleFunction;
use app\Modules\Blog\Actions\BlogActions;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\Pagination\Pagination;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;



class BlogModule extends ModuleFunction
{

    CONST DEFINITIONS = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
    /**
     * @var PostsTable
     */
    private $postsTable;

    /**
     * BlogModule constructor.
     * @param Router $router
     * @param RendererInterface $renderer
     * @param ContainerInterface $container
     * @param PostsTable $postsTable
     * @throws Exception
     */
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
     * @throws Exception
     */
    public function ListRoute(): void {
        $this->AddRoute(
            '/',
            [BlogActions::class, 'index'],
            'posts.index'
        );

        $this->AddRoute(
            '/{slug:[a-z\-0-9]+}-{id:[0-9]+}',
            [BlogActions::class, 'show'],
            'posts.show'
        );
    }
}