<?php
require 'public/index.php';


return [
    'environments' => [
        'default_database' => 'development',
        'development'      => [
            'adapter' => 'mysql',
            'host' => $app->getContainer()->get('database.host'),
            'name' => $app->getContainer()->get('database.name'),
            'user' => $app->getContainer()->get('database.username'),
            'pass' => $app->getContainer()->get('database.password')
        ]
    ]
];