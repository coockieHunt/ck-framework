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

    static function GetTextAfterChar(string $catch, string $char){
        return substr($char, strpos($char, $catch) +1 );
    }

    static function GetTextBeforeChar(string $catch, string $char){
        $arr = explode($catch, $char);
        return $arr[0];
    }
}