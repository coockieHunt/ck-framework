<?php


namespace ck_framework\Pagination;


use ck_framework\Pagination\Template\BootStrapTemplate;
use ck_framework\Router\Router;
use Exception;

class RendererPagination
{
    /**
     * @var array
     */
    private $Template;


    /**
     * RendererPagination constructor.
     */
    public function __construct()
    {
        $this->Template['bootstrap'] = BootStrapTemplate::class;
    }


    /**
     * @param string $template
     * @param Pagination $pagination
     * @param Router $router
     * @return array
     * @throws Exception
     */
    public function Renderer(string $template, Pagination $pagination, Router $router)
    {
        if (array_key_exists($template, $this->Template)) {

            $bar_element = $pagination->getBarElementDisplay();
            $current_step = $pagination->getCurrentStep();
            $Number_step = $pagination->getNumberStep();
            $redirect_uri = $pagination->getRedirectUri();
            $redirect_get = $pagination->getRedirectGet();

            $frac = $bar_element / 2;
            if (!is_float($frac)) {
                throw new Exception('bar element odd int');
            }

            $catch_pos = $current_step - intval($frac);
            $catch_neg = $current_step + intval($frac);

            $catch_number = [];
            foreach (range($catch_pos, $catch_neg) as $number) {
                $catch_number[] = $number;
            }

            $template = $this->Template[$template];
            $frame = $template::get('justify-content-left')['frame'];
            $current = $template::get('float: right;')['current'];

            $rslt[] = $frame['start'];

            $first = $catch_number[0];
            $end = $catch_number[count($catch_number) - 1];

            for ($i = 1; $i <= $Number_step; $i++) {
                $element = $template::get()['element'];
                if (in_array($i, $catch_number)) {
                    unset($redirect_get['p']);
                    $gen_uri = $router->generateUri($redirect_uri, [] , array_merge(["p" => $i] , $redirect_get));

                    switch ($i) {
                        case $first:
                            $uri = "href='{$gen_uri}'>{$template::get()['back']}";
                            break;
                        case $end:
                            $uri = "href='{$gen_uri}'>{$template::get()['next']}";
                            break;
                        default:
                            $uri = "href='{$gen_uri}'>{$i}";
                            break;
                    }

                    if ($i == $current_step) {
                        $element = $template::get('', $current)['element'];
                    }

                    $rslt[] = str_replace(":custom", $uri, $element);

                }
            }

            $rslt[] = $frame['end'];

            return $rslt;
        } else {
            throw new Exception('template found');
        }
    }
}