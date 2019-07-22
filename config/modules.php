<?php
/**
 * List of modules loading
 * EXAMPLE:
 *      $modules = [
 *          \modules\AdminModules\AdminModules::class,
 *          \modules\PostModules\Post::class
 *      ];
 */

use app\Modules\Admin\AdminModule;
use app\Modules\Blog\BlogModule;
use app\Modules\File\FileModule;
use app\Modules\Home\HomeModule;

$modules = [
    HomeModule::class,
    BlogModule::class,
    FileModule::class,
    AdminModule::class,
];
