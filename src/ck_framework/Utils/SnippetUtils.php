<?php


namespace ck_framework\Utils;


class SnippetUtils
{
    /**
     * check if php execution if not console
     * @return bool
     */
    static function IfNotCli(){
        if (php_sapi_name() !== "cli") {
            return true;
        }else{
            return false;
        }
    }

    /**
     * check if string contain specific char
     * @param string $catch
     * @param string $char
     * @return bool
     */
    static function IfStringContains(string $catch, string $char){
        if (strpos($char, $catch) !== false) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * catch text after specific character
     * @param string $catch
     * @param string $char
     * @return bool|string
     */
    static function GetTextAfterChar(string $catch, string $char){
        return substr($char, strpos($char, $catch) +1 );
    }

    /**
     * catch text before specific character
     * @param string $catch
     * @param string $char
     * @return mixed
     */
    static function GetTextBeforeChar(string $catch, string $char){
        $arr = explode($catch, $char);
        return $arr[0];
    }

    /**
     * get text between char
     * @param $string
     * @param $start
     * @param $end
     * @return bool|string
     */
    static function GetStringBetween($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }


    /**
     * return string formatted for html
     * @param array $args
     * @return string
     */
    static function ArrayArgsToHtml(array $args): string{
        return implode(' ', array_map(
            function ($v, $k) {
                if(is_array($v)){
                    return $k.'[]='.implode('&'.$k.'[]=', $v);
                }else{
                    return $k.'="'.$v. '"';
                }
            },
            $args,
            array_keys($args)
        ));
    }

    static function CheckBoxFormToBool(string $value){
        if ($value == 'on' || $value == 1){
            return true;
        }

        return false;
    }
}