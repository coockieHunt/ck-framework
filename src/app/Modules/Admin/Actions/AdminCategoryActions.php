<?php


namespace app\Modules\Admin\Actions;


use app\ModuleFunction;
use app\Modules\Admin\Model\CategoryModel;
use app\Modules\Blog\Table\CategoryTable;
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

class AdminCategoryActions extends ModuleFunction
{
    /**
     * @var CategoryTable
     */
    private $categoryTable;
    /**
     * @var FlashService
     */
    private $flash;

    /**
     * @var CategoryModel
     */
    private $categoryModel;


    /**
     * AdminCategoryActions constructor.
     * @param Router $router
     * @param RendererInterface $renderer
     * @param ContainerInterface $container
     * @param CategoryTable $categoryTable
     * @param CategoryModel $categoryModel
     * @param FlashService $flash
     * @throws Exception
     */
    public function __construct(
        Router $router,
        RendererInterface  $renderer,
        ContainerInterface $container ,
        CategoryTable $categoryTable,
        CategoryModel $categoryModel,
        FlashService $flash
    )
    {
        $dir = substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR));
        parent::init($router, $renderer, $container,  $dir);
        $this->categoryTable = $categoryTable;
        $this->flash = $flash;
        $this->categoryModel = $categoryModel;
    }

    public function category(){
        //set default pagination
        $redirect = 'admin.category';

        //parse get parameter
        $FilterSlug = '';$FilterName = '';
        if (!empty($_GET['name'])){$FilterName = $_GET['name'];};
        if (!empty($_GET['slug'])){$FilterSlug = $_GET['slug'];};

        //get count result
        $categoryCount = $this->categoryTable->CountFilter($FilterName, $FilterSlug);

        //set pagination
        if (!isset($_GET['p'])) {$current = 1;} else {$current = (int)$_GET['p'];}
        $Pagination = new Pagination(10, 9, $categoryCount[0], $redirect);
        $Pagination->setCurrentStep($current);

        //setup form search
        $formUri = $this->router->generateUri($redirect);
        $formClass = ['class' => 'pr-1 align-items-stretch ', 'style' => 'flex-grow: 1'];
        $form = $this->categoryModel->BuildFindCategoryForm($_GET, $formUri, $formClass);

        //get category list
        if (count($_GET) > 1){
            $category = $this->categoryTable->FindResultLimitFilter(
                $FilterName,
                $FilterSlug,
                $Pagination->GetLimit(),
                $Pagination->getDbElementDisplay()
            );
        }else{
            $category = $this->categoryTable->FindResultLimit($Pagination->GetLimit(), $Pagination->getDbElementDisplay());
        }

        $viewParams = ['form' => $form, 'categories' => $category, 'dataPagination' => $Pagination];
        return $this->Render('category\categories', $viewParams);
    }

    public function categoryNew(Request $request){
        //get body request
        $body = $request->getParsedBody();

        // build form
        $formUri = $this->router->generateUri('admin.category.new.POST');
        $formClass = ['class' => 'form-group'];
        $form = $this->categoryModel->BuildCategoryMangerForm($body, $formUri, $formClass);


        //process add post
        if ($request->getMethod() == 'POST'){
            //check if slug exist
            $SlugCheck = $this->categoryTable->FindBySlug($body['slug']);

            /* check form field */
            $validator = (new Validator($body))
                ->required('name', 'slug')
                ->empty('name', 'slug')
                ->isNotSlug('slug');

            if ($SlugCheck != false) {
                $validator->CustomError('slug', '"' . $body['slug'] . '" is slug and already used');
            }
            //process
            if ($validator->isValid()) {
                $this->categoryTable->NewCategory($body['name'], $body['slug']);
                $this->flash->success('category has been create');
                return $this->router->redirect('admin.category');
            }else{
                $errorList = '';
                foreach ($validator->getError() as $element){
                    $errorList = $errorList .
                        ' <br> - ' . $element;
                }
                $this->flash->error('category error :' . $errorList);
            }
        }

        return $this->Render('category\categoryNew',['form' => $form ]);
    }

    /**
     * edit specific category
     * @param Request $request
     * @return mixed|ResponseInterface
     * @throws Exception
     */
    public function categoryEdit(Request $request){
        $body = $request->getParsedBody();
        //get uri Attribute
        $RequestId = $request->getAttribute('id');

        //get article
        $category = $this->categoryTable->FindById($RequestId);
        if ($category == false){return $this->router->redirect('admin.index');}

        //setup form
        $formUri = $this->router->generateUri('admin.category.edit.POST', ['id' => $category->id]);
        $formClass = ['class' => 'form-group'];
        if (empty($body)){
            $body = ['name' => $category->name, 'slug' => $category->slug];
        }
        $form = $this->categoryModel->BuildCategoryMangerForm($body, $formUri, $formClass);

        //add post process
        if ($request->getMethod() == 'POST'){
            //get information
            $SlugCheck = $this->categoryTable->FindBySlug($body['slug']);
            /* check form field */
            $validator = (new Validator($body))
                ->required('name', 'slug')
                ->empty('name', 'slug')
                ->isNotSlug('slug');

            if ($SlugCheck != false && $SlugCheck->id != $RequestId) {
                $validator->CustomError('slug', '"' . $body['slug'] . '" is slug and already used');
            }

            //process
            if ($validator->isValid()) {
                $this->categoryTable->UpdateCategory($RequestId, $body['name'], $body['slug']);
                $this->flash->success('category has been update');
                return $this->router->redirect('admin.category');
            }else{
                $errorList = '';
                foreach ($validator->getError() as $element){
                    $errorList = $errorList .
                        ' <br> - ' . $element;
                }
                $this->flash->error('post error :' . $errorList);
            }
        }

        return $this->Render('category\categoryEdit', ['category' => $category, 'form' => $form]);
    }


    /**
     * delete specific category
     * @param Request $request
     * @return mixed|ResponseInterface
     */
    public function categoryDelete(Request $request){
        //get uri Attribute
        $RequestId = $request->getAttribute('id');
        //get article
        $category = $this->categoryTable->FindById($RequestId);
        if ($category == false){return $this->router->redirect('admin.index');}

        //process delete category
        if (isset($_GET['confirm'])){
            $this->categoryTable->DeleteById($RequestId);
            $this->flash->warning('category has been delete');
            return $this->router->redirect('admin.category');
        }

        return $this->Render('category\categoryDelete', ['category' => $category ]);
    }
}