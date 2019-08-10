<?php


namespace app\Modules\Admin\Model;


use ck_framework\FormBuilder\FormBuilder;

class PostModel
{
    public function BuildPostMangerForm(array $args, string $formUri, array $formClass) : FormBuilder{
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
            );

        return $form;
    }

    public function  BuildFindPostForm(array $args, string $formUri, array $formClass) : FormBuilder{
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