<?php

use ck_framework\TwigExtension\AssetTwigExtension;
use ck_framework\TwigExtension\RouterTwigExtension;
use ck_framework\TwigExtension\SelfCutTextTwigExtension;

include("AutoWire.php");

CONST SPACER = DIRECTORY_SEPARATOR;
$AutoWire = new AutoWire();

$config = [
    'view.patch' => SPACER . '..' . SPACER . 'app' . SPACER . 'Views',
    'default.style.src' => 'style',
    'twig.extension' => [
        SelfCutTextTwigExtension::class,
        AssetTwigExtension::class,
        RouterTwigExtension::class
    ]
];

return (array_merge($config, $AutoWire->getAutoWire()));