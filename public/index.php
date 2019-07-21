<?php

use ck_framework\App;
use ck_framework\Utils\SnippetUtils;
use DI\ContainerBuilder;
use GuzzleHttp\Psr7\ServerRequest;
use function Http\Response\send;

// setup var
$space = DIRECTORY_SEPARATOR;
require_once dirname(__DIR__) . $space . 'vendor' . $space . 'autoload.php';
require_once dirname(__DIR__) . $space . 'config' . $space . 'modules.php';

// setup container
$builder = new ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__) .  $space . 'config' . $space . 'config.php');
foreach ($modules as $module){
    if ($module::DEFINITIONS != null){
        $builder->addDefinitions($module::DEFINITIONS);
    }
}
$container = $builder->build();

//launch app
$app = new App($container, $modules);
if (SnippetUtils::IfNotCli()){
    $response = $app->run(ServerRequest::fromGlobals());
    send($response);
}
