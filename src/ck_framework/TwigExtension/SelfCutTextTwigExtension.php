<?php


namespace ck_framework\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SelfCutTextTwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction("SelfCut", [$this, 'SelfCutString']),
            new TwigFunction("SelfWarp", [$this, 'SelfWrappingString']),
        ];
    }

    /**
     * return self cut end string
     * @param string $string
     * @param int $start_char
     * @param string $length
     * @param string $end
     * @return string
     */
    public function SelfCutString(string $string, int $start_char, string $length, string $end = "..."): string {
        if( strlen( $string ) <= $length ) return $string;
        $str = mb_substr( $string, $start_char, $length - strlen( $end ) + 1, 'UTF-8');
        return substr( $str, 0, strrpos( $str,' ') ). ' ' .$end;
    }
}