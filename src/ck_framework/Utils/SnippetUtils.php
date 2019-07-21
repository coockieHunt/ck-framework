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
}