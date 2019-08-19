<?php


namespace app\Modules\Blog;
use app\ModuleFunction;
use app\Modules\Blog\Actions\BlogActions;
use app\Modules\Blog\Table\PostsTable;
use Exception;


class BlogModule
{

    CONST DEFINITIONS = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
    CONST MIGRATIONS = __DIR__ . DIRECTORY_SEPARATOR . 'Database' . DIRECTORY_SEPARATOR . 'migrations';
    CONST SEEDS =  __DIR__ . DIRECTORY_SEPARATOR . 'Database' . DIRECTORY_SEPARATOR . 'seeds';

    /**
     * @var ModuleFunction
     */
    private $moduleFunction;

    /**
     * BlogModule constructor.
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
     *          'true' [use module prefix !true default}
     *      );
     *
     * @return void
     * @throws Exception
     */
    public function ListRoute(): void {
        $this->moduleFunction->AddRoute(
            '/',
            [BlogActions::class, 'index'],
            'posts.index'
        );

        $this->moduleFunction->AddRoute(
            '/{slug:[a-z\-0-9]+}-{id:[0-9]+}',
            [BlogActions::class, 'show'],
            'posts.show'
        );
    }
}