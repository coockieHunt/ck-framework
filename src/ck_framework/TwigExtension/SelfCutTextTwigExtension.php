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

    /**
     * return self cut end string
     * @param string $string
     * @param int $start_char
     * @param int $_cut_count
     * @return string
     */
    public function SelfWrappingString(string $string, int $start_char, int $_cut_count){
        $stringCount = strlen($string);

        $number = ceil($stringCount / $_cut_count);
        $cut = [];
        for ($i = 1; $i <= $stringCount - 1; $i++) {
            $c = 0;
            dump($i);

            $c++;
        }
        $string = substr($string, $start_char, $_cut_count);
        dump($string);
        return $string;
    }
}