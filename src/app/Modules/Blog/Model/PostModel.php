<?php


namespace app\Modules\Blog\Model;


use app\Modules\Blog\Entity\PostsEntity;
use app\Modules\Blog\Table\CategoryTable;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\model\model;
use stdClass;

class PostModel extends model
{
    /**
     * @var PostsTable
     */
    protected $postsTable;
    /**
     * @var CategoryTable
     */
    protected $categoryTable;

    /**
     * PostModel constructor.
     * @param PostsTable $postsTable
     * @param CategoryTable $categoryTable
     */
    public function __construct(PostsTable $postsTable, CategoryTable $categoryTable)
    {
        $this->postsTable = $postsTable;
        $this->categoryTable = $categoryTable;
    }

    /**
     * Get post by id
     * @param int $id
     * @return mixed
     */
    public function GetPostById(int $id): PostsEntity{
        $post = $this->postsTable->FindById($id);
        if ($post){
            if ($post->id_category != 0){
                $category = $this->categoryTable->FindById($post->id_category);
                unset($post->id_category);
                $post->category = $category;
            }else{
                $post->category = new stdClass();
                $post->category->id = '0';
                $post->category->name = 'empty';
            }
        }

        return $post;
    }
}