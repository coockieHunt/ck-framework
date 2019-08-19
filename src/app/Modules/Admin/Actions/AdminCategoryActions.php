<?php


namespace app\Modules\Admin\Actions;


use app\ModuleFunction;
use app\Modules\Admin\Model\CategoryModel;
use app\Modules\Admin\Model\PostModel;
use app\Modules\Blog\Table\CategoryTable;
use ck_framework\Pagination\Pagination;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use ck_framework\Session\FlashService;
use ck_framework\Validator\Validator;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminCategoryActions
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
     * @var PostModel
     */
    private $postModel;
    /**
     * @var ModuleFunction
     */
    private $moduleFunction;

    /**
     * AdminCategoryActions constructor.
     * @param CategoryTable $categoryTable
     * @param CategoryModel $categoryModel
     * @param PostModel $postModel
     * @param ModuleFunction $moduleFunction
     * @param FlashService $flash
     * @throws Exception
     */
    public function __construct(
        CategoryTable $categoryTable,
        CategoryModel $categoryModel,
        PostModel $postModel,
        ModuleFunction $moduleFunction,
        FlashService $flash
    )
    {
        $dir = substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR));
        $this->categoryTable = $categoryTable;
        $this->flash = $flash;
        $this->categoryModel = $categoryModel;
        $this->postModel = $postModel;
        $this->moduleFunction = $moduleFunction;

        $this->moduleFunction->init($dir, $this);
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
        $formUri = $this->moduleFunction->getRouter()->generateUri($redirect);
        $form = $this->categoryModel->BuildFindCategoryForm($_GET, $formUri);

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
        return $this->moduleFunction->Render('category\categories', $viewParams);
    }

    public function categoryNew(Request $request){
        //get body request
        $body = $request->getParsedBody();

        // build form
        $formUri = $this->moduleFunction->getRouter()->generateUri('admin.category.new.POST');
        $form = $this->categoryModel->BuildCategoryMangerForm($body, $formUri);

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
                return $this->moduleFunction->getRouter()->redirect('admin.category');
            }else{
                $errorList = '';
                foreach ($validator->getError() as $element){
                    $errorList = $errorList .
                        ' <br> - ' . $element;
                }
                $this->flash->error('category error :' . $errorList);
            }
        }

        return $this->moduleFunction->Render('category\categoryNew',['form' => $form ]);
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
        if ($category == false){return $this->moduleFunction->getRouter()->redirect('admin.index');}

        //setup form
        $postsLinked = $this->categoryTable->getPostsLinked($RequestId);

        $formUri = $this->moduleFunction->getRouter()->generateUri('admin.category.edit.POST', ['id' => $category->id]);
        if (empty($body)){$body = ['name' => $category->name, 'slug' => $category->slug];}

        $formCategory = $this->categoryModel->BuildCategoryMangerForm($body, $formUri);
        $formChangeCategoryCustom = $this->categoryModel->BuildChangeCategoryCustom(
            $formUri,
            $postsLinked,
            $category->id,
            ['type','change_category']
        );
        $formChangeCategory = $this->categoryModel->BuildChangeCategory($formUri, $category->id, ['type','change_category']);


        //add post process
        if ($request->getMethod() == 'POST'){
            if (!isset($body['type'])){
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
                    return $this->moduleFunction->getRouter()->redirect('admin.category');
                }else{
                    $errorList = '';
                    foreach ($validator->getError() as $element){
                        $errorList = $errorList .
                            ' <br> - ' . $element;
                    }
                    $this->flash->error('post error :' . $errorList);
                }
            }else {
                if ($body['type'] == 'change_category') {
                    $custom = $body;
                    if (isset($body['custom'])){
                        $custom = [];
                        foreach ($postsLinked as $element) {
                            $custom[$element->id] = $body['custom'];
                        }
                    }

                    foreach ($custom as $key => $value){
                        $this->categoryTable->UpdatePostCategory($key, $value);
                    }

                    $this->flash->success('category has been update');
                    return $this->moduleFunction->getRouter()->redirect('admin.category');
                }
            }
        }

        return $this->moduleFunction->Render('category\categoryEdit',
            [
                'category' => $category,
                'formCategory' => $formCategory,
                'formChangeCategoryCustom' => $formChangeCategoryCustom,
                'formChangeCategory' => $formChangeCategory,
            ]
        );
    }


    /**
     * delete specific category
     * @param Request $request
     * @return mixed|ResponseInterface
     */
    public function categoryDelete(Request $request){
        $body = $request->getParsedBody();

        //get uri Attribute
        $RequestId = $request->getAttribute('id');
        //get article
        $category = $this->categoryTable->FindById($RequestId);
        $postsLinked = $this->categoryTable->getPostsLinked($RequestId);
        if ($category == false){return $this->router->redirect('admin.index');}

        //build form
        $formUri = $this->router->generateUri('admin.category.delete.POST', ['id' => $category->id]);
        $formCustom = $this->categoryModel->BuildChangeCategoryCustom($formUri, $postsLinked,$category->id);
        $form = $this->categoryModel->BuildChangeCategory($formUri, $category->id);

        //add post process
        if ($request->getMethod() == 'POST'){
            $custom = $body;
            if (isset($body['custom'])){
                $custom = [];
                foreach ($postsLinked as $element) {
                    $custom[$element->id] = $body['custom'];
                }
            }

            foreach ($custom as $key => $value){
                $this->categoryTable->UpdatePostCategory($key, $value);
            }

            $this->categoryTable->DeleteById($category->id);
            $this->flash->warning('category has been delete');
            return $this->router->redirect('admin.category');
        }

        return $this->Render('category\categoryDelete', [
                'category' => $category,
                'postsLinked' => $postsLinked,
                'formCustom' => $formCustom,
                'form' => $form,
            ]
        );
    }
}