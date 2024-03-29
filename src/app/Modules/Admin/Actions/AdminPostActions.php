<?php


namespace app\Modules\Admin\Actions;

use app\ModuleFunction;
use app\Modules\Admin\Model\PostModel;
use app\Modules\Blog\Table\CategoryTable;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\Pagination\Pagination;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use ck_framework\Session\FlashService;
use ck_framework\Utils\SnippetUtils;
use ck_framework\Validator\Validator;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminPostActions
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
     * @var PostModel
     */
    private $postModel;
    /**
     * @var CategoryTable
     */
    private $categoryTable;
    /**
     * @var ModuleFunction
     */
    private $moduleFunction;

    /**
     * AdminPostActions constructor.
     * @param PostsTable $postsTable
     * @param CategoryTable $categoryTable
     * @param FlashService $flash
     * @param PostModel $postModel
     * @param ModuleFunction $moduleFunction
     * @throws Exception
     */
    public function __construct(
        PostsTable $postsTable,
        CategoryTable $categoryTable,
        FlashService $flash,
        PostModel $postModel,
        ModuleFunction $moduleFunction
    )
    {
        $dir = substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR));
        $this->postsTable = $postsTable;
        $this->flash = $flash;
        $this->postModel = $postModel;
        $this->categoryTable = $categoryTable;
        $this->moduleFunction = $moduleFunction;

        $this->moduleFunction->init($dir, $this);
    }

    /**
     * list all post
     * @return mixed|ResponseInterface
     */
    public function posts(){
        //set default pagination
        if (!isset($_GET['p'])) {$current = 1;} else {$current = (int)$_GET['p'];}

        //parse get parameter
        $FilterContent = '';$FilterSlug = '';$FilterTitle = '';
        if (!empty($_GET['name'])){$FilterTitle = $_GET['name'];};
        if (!empty($_GET['slug'])){$FilterSlug = $_GET['slug'];};
        if (!empty($_GET['content'])){$FilterContent = $_GET['content'];};

        //get count result
        $postsCount = $this->postsTable->CountFilter($FilterTitle, $FilterSlug, $FilterContent);

        //setup form search
        $formUri =  $this->moduleFunction->getRouter()->generateUri('admin.posts');
        $form = $this->postModel->BuildFindPostForm($_GET, $formUri);

        //setup pagination
        $redirect = 'admin.posts';
        $Pagination = new Pagination(10, 9, $postsCount[0], $redirect, $_GET);
        $Pagination->setCurrentStep($current);
        //get result
        if (count($_GET) > 1){
            $posts = $this->postsTable->FindResultLimitFilter(
                $FilterTitle,
                $FilterSlug,
                $FilterContent,
                $Pagination->GetLimit(),
                $Pagination->getDbElementDisplay()
            );
        }else{
            $posts = $this->postsTable->FindResultLimit($Pagination->GetLimit(), $Pagination->getDbElementDisplay());
        }
        //render view
        return  $this->moduleFunction->Render('post\posts', ['form' => $form, 'posts' => $posts, 'dataPagination' => $Pagination]);
    }

    /**
     * create new post
     * @param Request $request
     * @return mixed|ResponseInterface
     * @throws Exception
     */
    public function postNew(Request $request){
        //get body request
        $body = $request->getParsedBody();

        // build form
        $formUri =  $this->moduleFunction->getRouter()->generateUri('admin.posts.new.POST');
        $form = $this->postModel->BuildPostMangerForm($body, $formUri);

        //process add post
        if ($request->getMethod() == 'POST'){
            //check if slug exist
            $SlugCheck = $this->postsTable->FindBySlug($body['slug']);

            /* check form field */
            $validator = (new Validator($body))
                ->required('name', 'slug', 'content')
                ->empty('name', 'slug', 'content')
                ->isNotSlug('slug');

            if ($SlugCheck != false) {
                $validator->CustomError('slug', '"' . $body['slug'] . '" is slug and already used');
            }
            //process
            if ($validator->isValid()) {
                $this->postsTable->NewPost($body['name'], $body['content'], $body['slug'], $body["active"], $body['category']);
                $this->flash->success('post has been create');
                return  $this->moduleFunction->getRouter()->redirect('admin.posts');
            }else{
                $errorList = '';
                foreach ($validator->getError() as $element){
                    $errorList = $errorList .
                        ' <br> - ' . $element;
                }
                $this->flash->error('post error :' . $errorList);
            }
        }

        return  $this->moduleFunction->Render('post\postNew',['form' => $form ]);
    }

    /**
     * edit specific post
     * @param Request $request
     * @return mixed|ResponseInterface
     * @throws Exception
     */
    public function postEdit(Request $request){
        $body = $request->getParsedBody();

        //get uri Attribute
        $RequestId = $request->getAttribute('id');

        //get article
        $post = $this->postModel->GetPostById($RequestId);
        if (!$post){return  $this->moduleFunction->getRouter()->redirect('admin.index');}

        //setup form
        $formUri =  $this->moduleFunction->getRouter()->generateUri('admin.posts.edit.POST', ['id' => $post->id]);
        $active = SnippetUtils::CheckBoxFormToBool($post->active);

        if (empty($body)){
            $post->active = $active;

            $body = [
                'name' => $post->name,
                'slug' => $post->slug,
                'content' => $post->content,
                'active' => $post->active,
                'category' => $post->category->name,
            ];
        }else{if (isset($body['active'])){$body['active'] = true;}else{$body['active'] = false;};}
        $form = $this->postModel->BuildPostMangerForm($body, $formUri);

        //add post process
        if ($request->getMethod() == 'POST'){
            //get information
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
                $this->postsTable->UpdatePost($RequestId, $body['name'], $body['content'], $body['slug'], $body['active'], $body['category']);
                $this->flash->success('post has been update');
                return  $this->moduleFunction->getRouter()->redirect('admin.posts');
            }else{
                $errorList = '';
                foreach ($validator->getError() as $element){
                    $errorList = $errorList .
                        ' <br> - ' . $element;
                }
                $this->flash->error('post error :' . $errorList);
            }
        }

        return  $this->moduleFunction->Render('post\postEdit', ['post' => $post, 'form' => $form]);
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
        if ($post == false){return  $this->moduleFunction->getRouter()->redirect('admin.index');}

        //process delete posts
        if (isset($_GET['confirm'])){
            $this->postsTable->DeleteById($RequestId);
            $this->flash->warning('post has been delete');
            return  $this->moduleFunction->getRouter()->redirect('admin.posts');
        }

        return  $this->moduleFunction->Render('post\postDelete', ['post' => $post ]);
    }
}