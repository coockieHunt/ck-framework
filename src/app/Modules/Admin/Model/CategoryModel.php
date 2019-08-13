<?php


namespace app\Modules\Admin\Model;


use ck_framework\FormBuilder\FormBuilder;

class CategoryModel
{
    public function  BuildFindCategoryForm(array $args, string $formUri, array $formClass) : FormBuilder{
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
            );
    }

    public function BuildCategoryMangerForm(array $args, string $formUri, array $formClass) : FormBuilder{
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
            );
        return $form;
    }
}