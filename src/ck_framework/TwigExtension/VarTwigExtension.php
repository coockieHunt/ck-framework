<?php


namespace ck_framework\TwigExtension;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class VarTwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction("varGet", [$this, 'GetVarGet']),
        ];
    }

    /**
     * return self cut end string
     * @param string|null $key
     * @return string
     */
    public function GetVarGet(string $key) {
        if ($key != '' and isset($_GET[$key])){
            return $_GET[$key];
        };
        return false;
    }
}