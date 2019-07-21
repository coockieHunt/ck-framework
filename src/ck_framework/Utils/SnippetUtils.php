<?php


namespace ck_framework\Utils;


class SnippetUtils
{
    static function IfNotCli(){
        if (php_sapi_name() !== "cli") {
            return true;
        }else{
            return false;
        }
    }
}