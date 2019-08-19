<?php


namespace app\Modules\Admin\Model;


use ck_framework\FormBuilder\FormBuilder;
use ck_framework\model\model;
use ck_framework\Router\Router;

class RouterModel extends model
{
    /**
     * @var Router
     */
    private $router;

    /**
     * RoutingModel constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function BuildFormBuilderForm($routeName, $args): FormBuilder{
        $uri = str_replace('.', '-', $routeName['name']);
        $formUri = $this->router->generateUri('admin.routing.build.POST', ['name' => $uri]);
        $formClass = ['class' => 'form-group'];
        $form = (new FormBuilder($formUri, 'POST', $formClass))
            ->setArgs($args);

        $params = $routeName['params'];
        foreach ($params as $key => $value){
            $form ->text($key,
                $value,
                $key . ' :',
                ['class' => 'form-control']);
        }

        return $form;
    }

    /**
     * @param $CatchRoute
     * @return mixed
     */
    public function issetRoute($CatchRoute) {
        $RouteList = $this->router->getRouteList();

        $catch = null;
        foreach ($RouteList as $route){
            if ($route['name'] == $CatchRoute){$catch = $route;}
        }

        if ($catch === null){return false;}else{return $catch;}
    }

    public function CountAll(): int {
        return count($RouteList = $this->router->getRouteList());
    }
}