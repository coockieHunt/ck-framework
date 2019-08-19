<?php


namespace app\Modules\Admin\Actions;


use app\ModuleFunction;
use app\Modules\Admin\Model\AdminModel;
use app\Modules\Admin\Model\RouterModel;
use ck_framework\Session\FlashService;
use ck_framework\Validator\Validator;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;


class AdminActions
{
    /**
     * @var FlashService
     */
    private $flash;

    /**
     * @var RouterModel
     */
    private $routerModel;
    /**
     * @var AdminModel
     */
    private $adminModel;
    /**
     * @var ModuleFunction
     */
    private $moduleFunction;

    /**
     * AdminActions constructor.
     * @param ModuleFunction $moduleFunction
     * @param FlashService $flash
     * @param RouterModel $routerModel
     * @param AdminModel $adminModel
     * @throws Exception
     */
    public function __construct(ModuleFunction $moduleFunction,FlashService $flash, RouterModel $routerModel, AdminModel $adminModel)
    {
        $dir = substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR));
        $this->flash = $flash;
        $this->routerModel = $routerModel;
        $this->adminModel = $adminModel;
        $this->moduleFunction = $moduleFunction;

        $this->moduleFunction->init($dir, $this);
    }

    /**
     * dashboard view
     * @return mixed
     */
    public function index(){
        return $this->moduleFunction->Render('index',["count" => $this->adminModel->CountData()]);
    }

    /**
     * list all route
     * @return mixed
     */
    public function routing(){
        $list = $this->moduleFunction->getRouter()->getRouteList();
        return $this->moduleFunction->Render('routing\routing', ['routing' => $list]);
    }

    public function routingBuild(Request $request){
        $body = $request->getParsedBody();

        //get uri Attribute
        $RequestName = $request->getAttribute('name');
        $NameParse = str_replace('-', '.', $RequestName);

        //check if route is declared
        $currentRoute = $this->routerModel->issetRoute($NameParse);
        if ($currentRoute === null){return $this->moduleFunction->getRouter()->redirect('admin.routing');};

        //build form
        $form = $this->routerModel->BuildFormBuilderForm($currentRoute, $body);

        if ($request->getMethod() == 'POST'){
            $params = $currentRoute['params'];
            $validator = (new Validator($body));
            foreach ($body as $key => $value){
                $regex = $params[$key];

                if (!preg_match_all('/'.$regex.'+/', $value)) {
                    $string = 'the value " %s " does not match the regex : %s';
                    $string = sprintf($string, $value, $regex);
                    $validator->CustomError($key, $string);
                }
            }

            if ($validator->isValid()){
                return $this->moduleFunction->getRouter()->redirect($currentRoute['name'], $body);
            }else{
                $errorList = '';
                foreach ($validator->getError() as $element){
                    $errorList = $errorList .
                        ' <br> - ' . $element;
                }
                $this->flash->error('post error :' . $errorList);
            }
        }

        return $this->moduleFunction->Render('routing\routingBuilder', ['routeName' => $currentRoute['name'], 'form' => $form]);
    }
}