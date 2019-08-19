<?php


namespace app\Modules\Blog\Model;


use app\Modules\Admin\Model\PostModel;
use app\Modules\Blog\Table\CategoryTable;
use ck_framework\model\model;

class CategoryModel extends model
{
    /**
     * @var CategoryTable
     */
    protected $categoryTable;
    /**
     * @var PostModel
     */
    protected $postModel;

    /**
     * CategoryModel constructor.
     * @param CategoryTable $categoryTable
     * @param PostModel $postModel
     */
    public function __construct(CategoryTable $categoryTable, PostModel $postModel)
    {
        $this->categoryTable = $categoryTable;
        $this->postModel = $postModel;
    }
}