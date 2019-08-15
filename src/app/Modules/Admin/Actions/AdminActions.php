<?php


namespace app\Modules\Admin\Actions;


use app\ModuleFunction;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\FormBuilder\FormBuilder;
use ck_framework\Renderer\RendererInterface;
use ck_framework\Router\Router;
use ck_framework\Session\FlashService;
use ck_framework\Validator\Validator;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;


class AdminActions extends ModuleFunction
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
     * AdminActions constructor.
     * @param Router $router
     * @param RendererInterface $renderer
     * @param ContainerInterface $container
     * @param FlashService $flash
     * @param PostsTable $postsTable
     * @throws Exception
     */
    public function __construct(
        Router $router,
        RendererInterface  $renderer,
        ContainerInterface $container ,
        FlashService $flash,
        PostsTable $postsTable
    )
    {
        $dir = substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR));
        parent::init($router, $renderer, $container,  $dir);
        $this->postsTable = $postsTable;
        $this->flash = $flash;
    }

    /**
     * dashboard view
     * @return mixed
     */
    public function index(){
        $count = [
            "route" => count($this->router->getRouteList()),
            "posts" => count($this->postsTable->FindAll()),
        ];

        return $this->Render('index',["count" => $count]);
    }

    /**
     * list all route
     * @return mixed
     */
    public function routing(){
        $list = $this->router->getRouteList();
        return $this->Render('routing\routing', ['routing' => $list]);
    }

    public function routingBuild(Request $request){
        $body = $request->getParsedBody();

        //get uri Attribute
        $RequestName = $request->getAttribute('name');
        $NameParse = str_replace('-', '.', $RequestName);
        $RouteList = $this->router->getRouteList();

        $thisRoute = null;
        foreach ($RouteList as $route){
            if ($route['name'] == $NameParse){
                $thisRoute = $route;
            }
        }
        if ($thisRoute === null){return $this->router->redirect('admin.routing');};

        $uri = str_replace('.', '-', $thisRoute['name']);
        $formUri = $this->router->generateUri('admin.routing.build.POST', ['name' => $uri]);
        $formClass = ['class' => 'form-group'];
        $form = (new FormBuilder($formUri, 'POST', $formClass))
            ->setArgs($body);

        $params = $thisRoute['params'];
        foreach ($params as $key => $value){
            $form ->text($key,
                $value,
                $key . ' :',
                ['class' => 'form-control']);
        }

        if ($request->getMethod() == 'POST'){
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
                return $this->router->redirect($thisRoute['name'], $body);
            }else{
                $errorList = '';
                foreach ($validator->getError() as $element){
                    $errorList = $errorList .
                        ' <br> - ' . $element;
                }
                $this->flash->error('post error :' . $errorList);
            }
        }

        return $this->Render('routing\routingBuilder', ['route' => $thisRoute, 'form' => $form]);
    }

    public function newDash(){
        return $this->Render('DashNew');
    }
}