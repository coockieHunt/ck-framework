<?php
require 'public/index.php';

$migrations = [];
$seeds = [];
foreach ($modules as $module) {
    if ($module::MIGRATIONS  != null) {
        $migrations[] = $module::MIGRATIONS;
    }

    if ($module::SEEDS  != null) {
        $seeds[] = $module::SEEDS;
    }
}

return [
    'paths' => [
        'migrations' => $migrations,
        'seeds' => $seeds
    ],

    'environments' => [
        'development' => [
            'adapter' => $container->get('database.adapter'),
            'host' => $container->get('database.host'),
            'name' => $container->get('database.name'),
            'user' => $container->get('database.user'),
            'pass' => $container->get('database.pass'),
            'collation' => 'utf8_unicode_ci',
            'charset' => 'utf8'
        ]
    ],
];