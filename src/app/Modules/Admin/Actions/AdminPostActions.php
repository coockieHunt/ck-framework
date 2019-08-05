<?php


namespace app\Modules\Admin\Actions;


use app\ModuleFunction;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\FormBuilder\FormBuilder;
use ck_framework\Pagination\Pagination;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use ck_framework\Session\FlashService;
use ck_framework\Validator\Validator;
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
     * @throws Exception
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

            /* check form field */
            $validator = (new Validator($body))
                ->required('name', 'slug', 'content')
                ->empty('name', 'slug', 'content')
                ->isNotSlug('slug');

            if ($SlugCheck != false && $SlugCheck->id != $RequestId) {
                $validator->CustomError('slug', '"' . $body['slug'] . '" is slug and already used');
            }

            //process
            if ($validator->isValid()) {
                $this->postsTable->UpdatePost($RequestId, $body['name'], $body['content'], $body['slug']);
                $this->flash->success('post has been update');
                return $this->router->redirect('admin.posts');
            }else{
                $errorList = '';
                foreach ($validator->getError() as $element){
                    $errorList = $errorList .
                        ' <br> - ' . $element;
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
     * @throws Exception
     */
    public function postNew(Request $request){
        $formUri = $this->router->generateUri('admin.posts.new');
        $formClass = ['class' => 'form-group'];

        $form = (new FormBuilder($formUri, 'POST', $formClass))
            ->text('name', 'name :', ['class' => 'form-control'])
            ->text('slug', 'slug :', ['class' => 'form-control'])
            ->textarea('content', 10,'content :', ['class' => 'form-control']);

        //process add post
        if ($request->getMethod() == 'POST'){
            $body = $request->getParsedBody();

            $form->setArgs($body);
            //check if slug exist
            $SlugCheck = $this->postsTable->FindBySlug($body['slug']);

            /* check form field */
            $validator = (new Validator($body))
                ->required('name', 'slug', 'content')
                ->empty('name', 'slug', 'content')
                ->isNotSlug('slug');

            //process
            if ($validator->isValid()) {
                $this->postsTable->NewPost($body['name'], $body['content'], $body['slug']);
                $this->flash->success('post has been create');
                return $this->router->redirect('admin.posts');
            }else{
                $errorList = '';
                foreach ($validator->getError() as $element){
                    $errorList = $errorList .
                        ' <br> - ' . $element;
                }
                $this->flash->error('post error :' . $errorList);
            }
        }

        return $this->Render('post\postNew',['form' => $form ]);
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
            $this->flash->warning('post has been delete');
            return $this->router->redirect('admin.posts');
        }

        return $this->Render('post\postDelete', ['post' => $post ]);
    }
}