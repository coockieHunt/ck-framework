<?php


namespace app\Modules\Admin\Actions;


use app\ModuleFunction;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\Pagination\Pagination;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminPostActions extends ModuleFunction
{
    /**
     * @var PostsTable
     */
    private $postsTable;

    public function __construct(Router $router, RendererInterface  $renderer, ContainerInterface $container , PostsTable $postsTable)
    {
        $dir = substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR));
        parent::init($router, $renderer, $container,  $dir);
        $this->postsTable = $postsTable;
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
            $body = $request->getParsedBody();

            //check if slug exist
            $SlugCheck = $this->postsTable->FindBySlug($body['slug']);

            $fail = [];
            if ($SlugCheck != false && $SlugCheck->id != $RequestId) {$fail[] = ['this slug and already used'];}
            if (preg_match('/\s/', $body['slug'])) {$fail[] = ['the slug must not contain whitespace'];}
            if (preg_match('/[0-9]+/', $body['slug'])) {$fail[] = ['the slug must not contain a number'];}
            if ($body['name'] == null){$fail[] = ['the name of the post should not be empty'];}
            if ($body['slug'] == null){$fail[] = ['the slug of the post should not be empty'];}
            if ($body['content'] == null){$fail[] = ['the content of the post should not be empty'];}

            if (empty($fail)) {
                $this->postsTable->UpdatePost($RequestId, $body['name'], $body['content'], $body['slug']);
                return $this->router->redirect('admin.posts');
            }else{
                $errorList = '';
                foreach ($fail as $element){$errorList =  $errorList . '    - ' . $element[0] . '<br>';}
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

            if ($body['name'] == null){$fail[] = [true, 'name_empty'];}
            if ($body['slug'] == null){$fail[] = [true, 'slug_empty'];}
            if ($body['content'] == null){$fail[] = [true, 'content_empty'];}

            if ($SlugCheck != false) {$fail[] = [true, 'slug_already_exists'];}
            if (preg_match('/\s/', $body['slug'])) {$fail[] = [true, 'slug_contains_whitespace'];}
            if (preg_match('/[0-9]+/', $body['slug'])) {$fail[] = [true, 'slug_contains_int'];}

            if (!$fail) {
                $this->postsTable->NewPost($body['name'], $body['content'], $body['slug']);
                return $this->router->redirect('admin.posts');
            }else{
                dd($fail);
            }
        }

        return $this->Render('postNew');
    }
}