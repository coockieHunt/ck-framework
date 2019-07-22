<?php


namespace app\Modules\Blog;
use app\ModuleFunction;
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
     */
    public function ListRoute(): void {
        $this->AddRoute(
            '/',
            [$this, 'index'],
            'posts.index'
        );

        $this->AddRoute(
            '/{slug:[a-z\-0-9]+}-{id:[0-9]+}',
            [$this, 'show'],
            'posts.show'
        );
    }

    public function show(Request $request){
        $RequestSlug = $request->getAttribute('slug');
        $RequestId = $request->getAttribute('id');

        $post = $this->postsTable->FindBySlug($RequestSlug);
        $postId = $post->id;

        if ($postId == $RequestId){
            return $this->Render("show" ,
                [
                    'post' => $post
                ]
            );
        }else{
            return $this->router->redirect("posts.show", ['slug' => $RequestSlug, 'id' => $post->id]);
        }
    }

    public function index()
    {
        if (!isset($_GET['p'])) {$current = 1;} else {$current = (int)$_GET['p'];}

        $postsCount = $this->postsTable->CountAll();
        $Pagination = new Pagination(
            10,
            5,
            $postsCount[0]
        );

        $Pagination->setCurrentStep($current);
        $posts = $this->postsTable->FindResultLimit($Pagination->GetLimit(), $Pagination->getDbElementDisplay());

        if (empty($posts)){return $this->router->redirect('posts.index', [], ['p' => 1]);}

        return $this->Render("index" ,
            [
                'posts' => $posts,
                'dataPagination' => $Pagination
            ]
        );
    }

}