<?php


namespace app\Modules\Admin\Model;


use app\Modules\Blog\Table\CategoryTable;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\model\model;

class AdminModel extends model
{
    /**
     * @var CategoryTable
     */
    private $categoryTable;
    /**
     * @var PostsTable
     */
    private $postsTable;
    /**
     * @var RouterModel
     */
    private $routerModel;

    /**
     * AdminModel constructor.
     * @param PostsTable $postsTable
     * @param CategoryTable $categoryTable
     * @param RouterModel $routerModel
     */
    public function __construct(PostsTable $postsTable, CategoryTable $categoryTable, RouterModel $routerModel)
    {
        $this->postsTable = $postsTable;
        $this->categoryTable = $categoryTable;
        $this->routerModel = $routerModel;
    }

    public function CountData(): array {
        return $count = [
            "route" => $this->routerModel->CountAll(),
            "posts" => $this->postsTable->CountAll(),
            "category" => $this->categoryTable->CountAll(),
        ];
    }
}