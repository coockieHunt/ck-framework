<?php


namespace ck_framework\Flash;


use ck_framework\Flash\Template\BootStrapTemplate;
use ck_framework\Utils\SnippetUtils;
use Exception;

class Flash
{
    /**
     * @var string
     */
    private $save_at;


    /**
     * RendererFlash constructor.
     * @param string $save_at
     * @throws Exception
     */
    public function __construct(string $save_at)
    {
        $valid_array = ['session'];

        if (!in_array($save_at, $valid_array)){throw new Exception('No valid option ' . $save_at);}
        $this->save_at = $save_at;
    }

    public function add(int $id, string $type, string $message){
        if(session_id() == '' || !isset($_SESSION)) {session_start();}

        $_SESSION['flash|' . $id] = [$type, $message];
    }

    public function push(?int $id = null){
        $allFlash = [];
        foreach ($_SESSION as $key => $value){
            if (SnippetUtils::IfStringContains('flash|', $key)){
                if ($id != null){
                    if (SnippetUtils::GetTextAfterChar('|', $key) == $id){
                        $name = $value[0];
                        $message = $value[1];
                        unset($_SESSION[$key]);
                        return BootStrapTemplate::get($name, $message);
                    }
                }else{
                    $name = $value[0];
                    $message = $value[1];
                    unset($_SESSION[$key]);
                    $allFlash[] =  BootStrapTemplate::get($name, $message);
                }
            }
        }
        return $allFlash;
    }
}