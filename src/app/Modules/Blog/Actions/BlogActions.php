<?php


namespace app\Modules\Blog\Actions;


use app\ModuleFunction;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\Pagination\Pagination;
use ParsedownExtra;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @property PostsTable postsTable
 */
class BlogActions
{
    /**
     * @var ModuleFunction
     */
    private $moduleFunction;

    /**
     * BlogActions constructor.
     * @param ModuleFunction $moduleFunction
     * @param PostsTable $postsTable
     * @throws \Exception
     */
    public function __construct(ModuleFunction $moduleFunction, PostsTable $postsTable)
    {
        $dir = substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR));
        $this->postsTable = $postsTable;
        $this->moduleFunction = $moduleFunction;

        $this->moduleFunction->init($dir, $this);
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

        //parse content
        $Parse = new ParsedownExtra();

        $post->content =  $Parse->text($post->content);

        //render page or redirect if uri not clear (slug or id not complete)
        if ($postId == $RequestId && $postSlug == $RequestSlug){
            return $this->moduleFunction->Render("show" , ['post' => $post]);
        }else{return $this->moduleFunction->getRouter()->redirect("posts.show", ['slug' => $postSlug, 'id' => $postId]);}
    }

    public function index()
    {
        $redirect = 'posts.index';
        $page = filter_input(INPUT_GET, 'p');
        if (!isset($page)) {$current = 1;} else {$current = (int)$_GET['p'];}


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

        if (empty($posts)){return $this->moduleFunction->getRouter()->redirect($redirect, [], ['p' => 1]);}
        return $this->moduleFunction->Render("index" ,
            [
                'posts' => $postsActive,
                'dataPagination' => $Pagination
            ]
        );
    }
}