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
            new TwigFunction('formBuild', [$this, 'formBuild'])
        ];
    }

    public function formBuild(FormBuilder $form)
    {
        echo implode("\n", $form->build());
    }
}