<?php
$explode = explode(DIRECTORY_SEPARATOR,__DIR__);
$ModulesName = strtolower(end($explode));
return [
    $ModulesName. ".prefix" => "file",
];
