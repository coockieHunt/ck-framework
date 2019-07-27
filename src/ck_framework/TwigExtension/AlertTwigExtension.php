<?php


namespace ck_framework\TwigExtension;


use ck_framework\Flash\Flash;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AlertTwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction("alert", [$this, 'AlertPush']),
        ];
    }

    public function AlertPush(?int $id = null): void
    {
        $flash = new Flash('session');
        if ($id != null){
            echo $flash->push($id);
        }else{
            $array = $flash->push();
            foreach ($array as $element){
                echo $element;}
        }
    }
}