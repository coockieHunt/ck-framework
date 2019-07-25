<?php


namespace app\Modules\Admin;


use app\ModuleFunction;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\Pagination\Pagination;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Exception;
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
     *          'POST' (method request !GET default
     *          'true' [use module prefix !true default}
     *      );
     *
     * @return void
     * @throws Exception
     */
    public function ListRoute(): void {
        $this->AddRoute(
            '/',
            [$this, 'index'],
            'admin.index'
        );

        $this->AddRoute(
            '/route',
            [$this, 'routing'],
            'admin.routing'
        );

        $this->AddRoute(
            '/posts',
            [$this, 'posts'],
            'admin.posts'
        );

        $this->AddRoute(
            '/posts/edit/{id:[0-9]+}',
            [$this, 'postEdit'],
            'admin.posts.edit'
        );

        $this->AddRoute(
            '/posts/new',
            [$this, 'postNew'],
            'admin.posts.new'
        );

        $this->AddRoute(
            '/posts/new',
            [$this, 'postNew'],
            'admin.posts.new.post',
            'POST'
        );

        $this->AddRoute(
            '/posts/edit/{id:[0-9]+}',
            [$this, 'postEdit'],
            'admin.posts.edit.post',
            'POST'
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
        $RequestId = $request->getAttribute('id');

        //get article
        $post = $this->postsTable->FindById($RequestId);

        if ($post == false){return $this->router->redirect('admin.index');}

        if ($request->getMethod() == 'POST'){
            $fail = false;

            $body = $request->getParsedBody();

            //check if slug exist
            $SlugCheck = $this->postsTable->FindBySlug($body['slug']);

            if ($SlugCheck != false && $SlugCheck->id != $RequestId) {$fail[] = [true, 'slug_already_exists'];}
            if (preg_match('/\s/', $body['slug'])) {$fail[] = [true, 'slug_contains_whitespace'];}
            if (preg_match('/[0-9]+/', $body['slug'])) {$fail[] = [true, 'slug_contains_int'];}

            if (!$fail) {
                $this->postsTable->UpdatePost($RequestId, $body['name'], $body['content'], $body['slug']);
                return $this->router->redirect('admin.posts.edit',
                    [
                        'id' => $RequestId,
                    ]
                );
            }
        }

        return $this->Render('postEdit',
            [
                'post' => $post,
            ]
        );
    }

    public function postNew(Request $request){
        if ($request->getMethod() == 'POST'){
            $fail = false;
            $body = $request->getParsedBody();

            //check if slug exist
            $SlugCheck = $this->postsTable->FindBySlug($body['slug']);

            if ($SlugCheck != false) {$fail[] = [true, 'slug_already_exists'];}
            if (preg_match('/\s/', $body['slug'])) {$fail[] = [true, 'slug_contains_whitespace'];}
            if (preg_match('/[0-9]+/', $body['slug'])) {$fail[] = [true, 'slug_contains_int'];}

            if (!$fail) {
                return $this->router->redirect('admin.posts.new');
            }else{
                dd($fail);
            }
        }

        return $this->Render('postNew');
    }
}