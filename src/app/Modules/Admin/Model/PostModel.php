<?php


namespace app\Modules\Admin\Model;

use app\Modules\Blog\Model\PostModel as BlogPostModel;
use ck_framework\FormBuilder\FormBuilder;

class PostModel extends BlogPostModel
{
    /**
     * generate form Manger for post
     * @param array $args
     * @param string $formUri
     * @return FormBuilder
     */
    public function BuildPostMangerForm(array $args, string $formUri) : FormBuilder{
        $formClass = ['class' => 'form-group'];
        $categorySelect = $this->BuildArraySelect($this->categoryTable->FindAll(), 'id', 'name');
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
     * @return FormBuilder
     */
    public function BuildFindPostForm(array $args, string $formUri) : FormBuilder{
        $formClass = ['class' => 'pr-1 align-items-stretch ', 'style' => 'flex-grow: 1'];
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
}