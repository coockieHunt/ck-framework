<?php


namespace app\Modules\Admin\Model;


use app\Modules\Blog\Model\CategoryModel as BlogCategoryModel;
use ck_framework\FormBuilder\FormBuilder;

class CategoryModel extends BlogCategoryModel
{

    /**
     * Build find category form
     * @param array $args
     * @param string $formUri
     * @return FormBuilder
     */
    public function  BuildFindCategoryForm(array $args, string $formUri) : FormBuilder{
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
            );
    }

    /**
     * build category manager form
     * @param array $args
     * @param string $formUri
     * @return FormBuilder
     */
    public function BuildCategoryMangerForm(array $args, string $formUri) : FormBuilder{
        $formClass = ['class' => 'form-group'];
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

    /**
     * Build form change custom category
     * @param string $formUri
     * @param array $Category
     * @param int $category_id
     * @param array $hidden
     * @return FormBuilder
     */
    public function BuildChangeCategoryCustom(string $formUri, array $Category, int $category_id, ?array $hidden = []): FormBuilder{
        $all_category = $this->categoryTable->FindAll();
        foreach ($all_category as $key => $value){
            if ($value->id == $category_id){
                unset($all_category[$key]);
            }
        }

        $formCategory = $this->BuildArraySelect($all_category, 'id', 'name');
        $formClass = ['class' => 'form-group'];

        $form = (new FormBuilder($formUri, 'POST', $formClass));
        foreach ($Category as $element){
            $form->select($element->id,
                $formCategory,
                $element->name,
                ['class' => 'form-control']
            );
        }

        if (isset($hidden)){
            $form->hidden($hidden[0],
                $hidden[1]
            );
        }

        return $form;
    }

    /**
     * Build form change category
     * @param string $formUri
     * @param int $category_id
     * @param array $hidden
     * @return FormBuilder
     */
    public function BuildChangeCategory(string $formUri, int $category_id, ?array $hidden = []): FormBuilder{
        $all_category = $this->categoryTable->FindAll();
        foreach ($all_category as $key => $value){
            if ($value->id == $category_id){
                unset($all_category[$key]);
            }
        }
        $formCategory = $this->postModel->BuildArraySelect($all_category, 'id', 'name');
        $formClass = ['class' => 'form-group'];

        $form = (new FormBuilder($formUri, 'POST', $formClass))
            ->select('custom',
                $formCategory,
                'change category :',
                ['class' => 'form-control']
            );

        if (isset($hidden)){
            $form->hidden($hidden[0],
                $hidden[1]
            );
        }

        return $form;
    }
}