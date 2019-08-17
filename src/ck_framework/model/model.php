<?php


namespace ck_framework\model;


class model
{
    /**
     * parse object for select
     * @param $array (object)
     * @param $key (data)
     * @param $value (display)
     * @return array
     */
    public function BuildArraySelect($array , $key, $value):array {
        $arraySelect = [];
        foreach ($array as $element){$arraySelect[$element->$key] = $element->$value;}
        return $arraySelect;
    }
}