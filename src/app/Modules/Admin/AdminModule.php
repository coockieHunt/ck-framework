<?php


namespace app\Modules\Admin;


use app\ModuleFunction;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\Pagination\Pagination;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

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

        $this->AddRoute(
            '/posts/edit/{slug:[a-z\-0-9]+}-{id:[0-9]+}',
            [$this, 'postEdit'],
            'admin.posts.edit'
        );
    }

    public function index(){
        $count = [
            "route" => count($this->router->getRouteList()),
            "posts" => count($this->postsTable->FindAll()),
        ];

       return $this->Render('index',[
           "count" => $count
       ]);
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
        $redirect = 'admin.posts';

        if (!isset($_GET['p'])) {$current = 1;} else {$current = (int)$_GET['p'];}

        $postsCount = $this->postsTable->CountAll();
        $Pagination = new Pagination(
            10,
            9,
            $postsCount[0],
            $redirect
        );

        $Pagination->setCurrentStep($current);
        $posts = $this->postsTable->FindResultLimit($Pagination->GetLimit(), $Pagination->getDbElementDisplay());

        if (empty($posts)){return $this->router->redirect($redirect, [], ['p' => 1]);}

        return $this->Render('posts',
            [
                'posts' => $posts,
                'dataPagination' => $Pagination
            ]
        );
    }

    public function postEdit(Request $request){
        //get uri Attribute
        $RequestSlug = $request->getAttribute('slug');
        $RequestId = $request->getAttribute('id');

        //try get article
        $post = $this->postsTable->FindBySlug($RequestSlug);
        if (empty($post)){$post = $this->postsTable->FindById($RequestId);}

        return $this->Render('postEdit',
            [
                'post' => $post,
            ]
        );
    }
}