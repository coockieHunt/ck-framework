<?php
/**
 * List of modules loading
 * EXAMPLE:
 *      $modules = [
 *          \modules\AdminModules\AdminModules::class,
 *          \modules\PostModules\Post::class
 *      ];
 */

use app\Modules\Blog\BlogModule;

$modules = [
    BlogModule::class
];
