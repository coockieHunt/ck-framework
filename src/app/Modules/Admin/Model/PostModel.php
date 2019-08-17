<?php


namespace app\Modules\Admin\Model;


use app\Modules\Blog\Table\CategoryTable;
use app\Modules\Blog\Table\PostsTable;
use ck_framework\FormBuilder\FormBuilder;
use ck_framework\model\model;

class PostModel extends model
{
    /**
     * @var PostsTable
     */
    private $postsTable;
    /**
     * @var CategoryTable
     */
    private $categoryTable;

    public function __construct(PostsTable $postsTable, CategoryTable $categoryTable)
    {
        $this->postsTable = $postsTable;
        $this->categoryTable = $categoryTable;
    }

    /**
     * generate form Manger for post
     * @param array $args
     * @param string $formUri
     * @param array $formClass
     * @param array $categorySelect
     * @return FormBuilder
     */
    public function BuildPostMangerForm(array $args, string $formUri, array $formClass, array $categorySelect) : FormBuilder{
        $form = (new FormBuilder($formUri, 'POST', $formClass))
            ->setArgs($args)
            ->text('name',
                'the creators of the original work give their opinion on the remake',
                'title :',
                ['class' => 'form-control']
            )
            ->text('slug',
                'this-and-slug',
                'slug :',
                ['class' => 'form-control']
            )
            ->textarea('content', 10,'content :', ['class' => 'form-control'])
            ->addCategory('p', "Parameters :", ['class' => 'mb-1'])
            ->checkbox('active',
                ['class' => 'form-check'],
                true,
                'active',
                ['class' => 'form-check-input']
            )
            ->select('category',
                $categorySelect,
                null,
                ['class' => 'form-control']
            );

        return $form;
    }

    /**
     * generate form for find specific post
     * @param array $args
     * @param string $formUri
     * @param array $formClass
     * @return FormBuilder
     */
    public function BuildFindPostForm(array $args, string $formUri, array $formClass) : FormBuilder{
        return (new FormBuilder($formUri, 'GET', $formClass))
            ->setArgs($args)
            ->text('name',
                'Title',
                null,
                ['class' => 'form-control']
            )
            ->text('slug',
                'slug',
                null,
                ['class' => 'form-control']
            )
            ->text('content',
                'content',
                null,
                ['class' => 'form-control']
            );
    }

    public function GetPostById(int $id){
        $post = $this->postsTable->FindById($id);
        if ($post){
            $category = $this->categoryTable->FindById($post->id_category);
            unset($post->id_category);
            $post->category = $category;
        }

        return $post;
    }
}