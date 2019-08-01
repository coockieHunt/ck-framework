<?php


namespace ck_framework\Validator;


use Exception;

class ValidatorService
{
    /**
     * @var array
     */
    private $params;

    /**
     * @var array
     */
    private $error;

    /**
     * ValidatorService constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * check if value exist or empty
     * @throws Exception
     */
    public function required(){
        $args = func_get_args();
        foreach ($args as $element){
            if(!array_key_exists($element, $this->params)){
                $this->setError($element, __FUNCTION__ );
            }else{
                $value = $this->getParam($element);
                if ($value == null || $value == ''){ $this->setError($element, 'empty' );}
            }
        }
    }

    /**
     * check if string min length
     * @param int $length
     * @param array $key
     * @throws Exception
     */
    public function minLength(int $length, array $key){
        foreach ($key as $element){
            $element = $this->getParam($element);
            if(strlen($element) < $length){
                $this->setError($element, __FUNCTION__ );
            }
        }
    }

    /**
     * check if string max length
     * @param int $length
     * @param array $key
     * @throws Exception
     */
    public function maxLength(int $length, array $key){
        foreach ($key as $element){
            $element = $this->getParam($element);
            if(strlen($element) > $length){
                $this->setError($element, __FUNCTION__);
            }
        }
    }

    /**
     * check if string is min length end maw length
     * @param int $Minlength
     * @param int $Maxlength
     * @param array $key
     * @throws Exception
     */
    public function length(int $Minlength, int $Maxlength, array $key){
        $range = range($Minlength, $Maxlength);
        foreach ($key as $element){
            $element = $this->getParam($element);
            $countElement = strlen($element);
            if(!in_array ($countElement, $range)){
                $this->setError($element, __FUNCTION__ );
            }
        }
    }

    /**
     * check if slug valid
     * @param string $val
     * @throws Exception
     */
    public function isNotSlug(string $val){
        $slug = $this->getParam($val);
        if (preg_match('/\s/', $slug) || preg_match('/[0-9]+/', $slug)){
            $this->setError($slug, __FUNCTION__ );
        }
    }

    /**
     * get parameter value
     * @param string $val
     * @return mixed
     * @throws Exception
     */
    public function getParam(string $val){
        if(isset($this->params[$val])){return $this->params[$val];}
        throw new Exception('Validator key not exist.');
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $key
     * @param string $value
     */
    private function setError(string $key, string $value): void
    {
        $this->error[$key] = $value;
    }

}