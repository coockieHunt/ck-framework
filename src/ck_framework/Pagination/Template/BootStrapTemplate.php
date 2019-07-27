<?php


namespace ck_framework\Pagination\Template;


class BootStrapTemplate
{
    /**
     * BootStrapPaginationTemplate constructor.
     * @param string|null $CustomFrameClass
     * @param string $CustomElementClass
     * @return array
     */
    static function get(?string $CustomFrameClass = '', string $CustomElementClass = '')
    {
        return [
            'frame' => [
                'start' => '<nav><ul class="pagination ' . $CustomFrameClass . '">',
                'end' => '</ul></nav>'
            ],

            'current' => 'active',
            'next' => '>>',
            'back' => '<<',

            'element' => '<li class="page-item  ' . $CustomElementClass . '"><a class="page-link" :custom </a></li>'
        ];
    }
}

