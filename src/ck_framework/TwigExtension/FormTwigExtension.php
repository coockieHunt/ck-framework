<?php


namespace ck_framework\TwigExtension;


use ck_framework\FormBuilder\FormBuilder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormTwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('formStart', [$this, 'formStart']),
            new TwigFunction('formEnd', [$this, 'formEnd']),
            new TwigFunction('formBuild', [$this, 'formBuild']),
            new TwigFunction('formSubmit', [$this, 'formSubmit']),
        ];
    }

    /**
     * initialise form
     * @param FormBuilder $form
     */
    public function formStart(FormBuilder $form)
    {
        echo  $form->start();
    }

    /**
     * generate input
     * @param FormBuilder $form
     */
    public function formBuild(FormBuilder $form)
    {
        echo implode("\n", $form->build());
    }

    /**
     * generate submit input
     * @param FormBuilder $form
     * @param string $content
     * @param array|null $args
     */
    public function formSubmit(FormBuilder $form, string $content, ?array $args = []){
        echo implode("\n", $form->submit($content, $args));
    }

    /**
     * close form
     * @param FormBuilder $form
     */
    public function formEnd(FormBuilder $form)
    {
        echo  $form->end();
    }


}