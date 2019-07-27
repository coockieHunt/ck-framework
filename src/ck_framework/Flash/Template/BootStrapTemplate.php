<?php


namespace ck_framework\Flash\Template;


class BootStrapTemplate
{
    /**
     * BootStrapPaginationTemplate constructor.
     * @param string $type
     * @param string $content
     * @return string
     */
    static function get(string $type = 'alert-success', string $content = "")
    {
        return '<div class="alert '. $type . '" role="alert">  ' . $content . ' </div>';
    }
}