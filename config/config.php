<?php

use ck_framework\TwigExtension\AssetTwigExtension;
use ck_framework\TwigExtension\RouterTwigExtension;
use ck_framework\TwigExtension\SelfCutTextTwigExtension;

include("AutoWire.php");

CONST SPACER = DIRECTORY_SEPARATOR;
$AutoWire = new AutoWire();

$config = [
    //development
    'development' => true,

    //configuration
    'view.patch' => SPACER . '..' . SPACER . 'app' . SPACER . 'Views',
    'default.style.src' => 'style',

    //database connect
    'database.adapter' => 'mysql',
    'database.host' => 'localhost',
    'database.name' => 'ck',
    'database.user' => 'root',
    'database.pass' => '',

    //twig
    'twig.environment' => [
        'debug' => true
    ],

    'twig.extension' => [
        SelfCutTextTwigExtension::class,
        AssetTwigExtension::class,
        RouterTwigExtension::class
    ]
];

return (array_merge($config, $AutoWire->getAutoWire()));