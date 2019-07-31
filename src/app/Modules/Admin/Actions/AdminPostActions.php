<?php


namespace app\Modules\Admin\Actions;


use app\ModuleFunction;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\Pagination\Pagination;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use ck_framework\Session\FlashService;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminPostActions extends ModuleFunction
{
    /**
     * @var PostsTable
     */
    private $postsTable;
    /**
     * @var FlashService
     */
    private $flash;

    /**
     * AdminPostActions constructor.
     * @param Router $router
     * @param RendererInterface $renderer
     * @param ContainerInterface $container
     * @param PostsTable $postsTable
     * @param FlashService $flash
     * @throws Exception
     */
    public function __construct(
        Router $router,
        RendererInterface  $renderer,
        ContainerInterface $container ,
        PostsTable $postsTable,
        FlashService $flash
    )
    {
        $dir = substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR));
        parent::init($router, $renderer, $container,  $dir);
        $this->postsTable = $postsTable;
        $this->flash = $flash;
    }

    /**
     * list all post
     * @return mixed|ResponseInterface
     */
    public function posts(){

        //setup pagination
        $redirect = 'admin.posts';
        if (!isset($_GET['p'])) {$current = 1;} else {$current = (int)$_GET['p'];}
        $postsCount = $this->postsTable->CountAll();
        $Pagination = new Pagination(10, 9, $postsCount[0], $redirect);
        $Pagination->setCurrentStep($current);

        $posts = $this->postsTable->FindResultLimit($Pagination->GetLimit(), $Pagination->getDbElementDisplay());


        //render view
        return $this->Render('post\posts', ['posts' => $posts, 'dataPagination' => $Pagination]);
    }

    /**
     * edit specific post
     * @param Request $request
     * @return mixed|ResponseInterface
     */
    public function postEdit(Request $request){
        //get uri Attribute
        $RequestId = $request->getAttribute('id');

        //get article
        $post = $this->postsTable->FindById($RequestId);
        if ($post == false){return $this->router->redirect('admin.index');}

        //add post process
        if ($request->getMethod() == 'POST'){
            //get information
            $body = $request->getParsedBody();
            $SlugCheck = $this->postsTable->FindBySlug($body['slug']);

            //check post error
            $fail = [];
            if ($SlugCheck != false && $SlugCheck->id != $RequestId) {$fail[] = 'this slug and already used';}
            if (preg_match('/\s/', $body['slug'])) {$fail[] = 'the slug must not contain whitespace';}
            if (preg_match('/[0-9]+/', $body['slug'])) {$fail[] = 'the slug must not contain a number';}
            if ($body['name'] == null){$fail[] = 'the name of the post should not be empty';}
            if ($body['slug'] == null){$fail[] = 'the slug of the post should not be empty';}
            if ($body['content'] == null){$fail[] = 'the content of the post should not be empty';}

            //process
            if (empty($fail)) {
                $this->postsTable->UpdatePost($RequestId, $body['name'], $body['content'], $body['slug']);
                $this->flash->success('post has been update');
                return $this->router->redirect('admin.posts');
            }else{
                $errorList = '';
                foreach ($fail as $element){
                    $errorList = $errorList .  '<br>-' . $element;
                }
                $this->flash->error('post error :' . $errorList);
            }
        }

        return $this->Render('post\postEdit', ['post' => $post,]);
    }

    /**
     * create new post
     * @param Request $request
     * @return mixed|ResponseInterface
     */
    public function postNew(Request $request){
        //process add post
        if ($request->getMethod() == 'POST'){
            $body = $request->getParsedBody();

            //check if slug exist
            $SlugCheck = $this->postsTable->FindBySlug($body['slug']);

            //check if form contain error
            $fail = [];
            if (preg_match('/\s/', $body['slug'])) {$fail[] = ['the slug must not contain whitespace'];}
            if (preg_match('/[0-9]+/', $body['slug'])) {$fail[] = ['the slug must not contain a number'];}
            if ($body['name'] == null){$fail[] = ['the name of the post should not be empty'];}
            if ($body['slug'] == null){$fail[] = ['the slug of the post should not be empty'];}
            if ($body['content'] == null){$fail[] = ['the content of the post should not be empty'];}

            //process
            if (empty($fail)) {
                $this->postsTable->NewPost($body['name'], $body['content'], $body['slug']);
                return $this->router->redirect('admin.posts');
            }else{
                dd($fail);
            }
        }

        return $this->Render('post\postNew');
    }

    /**
     * delete specific post
     * @param Request $request
     * @return mixed|ResponseInterface
     */
    public function postDelete(Request $request){
        //get uri Attribute
        $RequestId = $request->getAttribute('id');
        //get article
        $post = $this->postsTable->FindById($RequestId);
        if ($post == false){return $this->router->redirect('admin.index');}

        if (isset($_GET['confirm'])){
            $this->postsTable->DeleteById($RequestId);
            return $this->router->redirect('admin.posts');
        }

        return $this->Render('post\postDelete', ['post' => $post ]);
    }
}