<?php


namespace app\Modules\Blog\Actions;


use app\ModuleFunction;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\Pagination\Pagination;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @property PostsTable postsTable
 */
class BlogActions extends ModuleFunction
{
    public function __construct(Router $router, RendererInterface  $renderer, ContainerInterface $container, PostsTable $postsTable)
    {
        $dir = substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR));
        parent::init($router, $renderer, $container,  $dir);
        $this->postsTable = $postsTable;
    }

    public function show(Request $request){
        //get uri Attribute
        $RequestSlug = $request->getAttribute('slug');
        $RequestId = $request->getAttribute('id');

        //try get article
        $post = $this->postsTable->FindBySlug($RequestSlug);
        if (empty($post)){$post = $this->postsTable->FindById($RequestId);}

        //get id end slug request
        $postId = $post->id;
        $postSlug = $post->slug;

        //render page or redirect if uri not clear (slug or id not complete)
        if ($postId == $RequestId && $postSlug == $RequestSlug){
            return $this->Render("show" , ['post' => $post]);
        }else{return $this->router->redirect("posts.show", ['slug' => $postSlug, 'id' => $postId]);}
    }

    public function index()
    {
        $redirect = 'posts.index';

        if (!isset($_GET['p'])) {$current = 1;} else {$current = (int)$_GET['p'];}


        $postsCount = $this->postsTable->CountAll();
        $Pagination = new Pagination(
            10,
            5,
            $postsCount[0],
            $redirect

        );

        $Pagination->setCurrentStep($current);
        $posts = $this->postsTable->FindResultLimit($Pagination->GetLimit(), $Pagination->getDbElementDisplay());

        $postsActive = [];
        foreach ($posts as $post){
            if ($post->active){
                $postsActive[] = $post;
            }
        }

        if (empty($posts)){return $this->router->redirect($redirect, [], ['p' => 1]);}
        return $this->Render("index" ,
            [
                'posts' => $postsActive,
                'dataPagination' => $Pagination
            ]
        );
    }
}