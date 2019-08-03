<?php


namespace ck_framework\Validator;


use Exception;

class Validator
{
    /**
     * @var array
     */
    private $params;

    /**
     * @var array
     */
    private $error = [];

    /**
     * Validator constructor.
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
    public function required(): self{
        $args = func_get_args();
        foreach ($args as $arg){
            if(!array_key_exists($arg, $this->params)){
                $this->setError($arg, __FUNCTION__);
            }
        }
        return $this;
    }

    public function empty(): self{
        $args = func_get_args();
        foreach ($args as $arg){
            $value = $this->getParam($arg);
            if ($value == null || $value == ''){ $this->setError($arg, __FUNCTION__ );}
        }
        return $this;
    }

    /**
     * check if string min length
     * @param int $length
     * @param array $keys
     * @return Validator
     * @throws Exception
     */
    public function minLength(int $length, array $keys): self{
        foreach ($keys as $key){
            $key = $this->getParam($key);
            if(strlen($key) < $length){
                $this->setError($key, __FUNCTION__ , [$length]);
            }
        }
        return $this;
    }

    /**
     * check if string max length
     * @param int $length
     * @param array $keys
     * @return Validator
     * @throws Exception
     */
    public function maxLength(int $length, array $keys): self{
        foreach ($keys as $key){
            $key = $this->getParam($key);
            if(strlen($key) > $length){
                $this->setError($key, __FUNCTION__, [$length]);
            }
        }
        return $this;
    }

    /**
     * check if string is min length end maw length
     * @param int $Minlength
     * @param int $Maxlength
     * @param array $keys
     * @return Validator
     * @throws Exception
     */
    public function length(int $Minlength, int $Maxlength, array $keys): self{
        $range = range($Minlength, $Maxlength);
        foreach ($keys as $key){
            $key = $this->getParam($key);
            $countElement = strlen($key);
            if(!in_array ($countElement, $range)){
                $this->setError($key, __FUNCTION__, [$Minlength, $Maxlength] );
            }
        }
        return $this;
    }

    /**
     * check if slug valid
     * @param string $val
     * @return Validator
     * @throws Exception
     */
    public function isNotSlug(string $val): self{
        $slug = $this->getParam($val);
        if (preg_match('/\s/', $slug) || preg_match('/[0-9]+/', $slug)){
            $this->setError($slug, __FUNCTION__ );
        }
        return $this;
    }

    /**
     * get parameter value
     * @param string $val
     * @return mixed
     * @throws Exception
     */
    private function getParam(string $val){
        if(isset($this->params[$val])){return $this->params[$val];}
        throw new Exception('Validator key not exist.');
    }

    /**
     * set error
     * @param string $key
     * @param string $value
     * @param array $attributes
     */
    private function setError(string $key, string $value, array $attributes = []): void
    {
        $error = new ValidatorError($key, $value , $attributes);
        $this->error[$key] = (string)$error;
    }

    /**
     * return error
     * @return array
     */
    public function getError(){return $this->error;}

    /**
     * check if array error empty
     * @return bool
     */
    public function isValid(): bool{
        if ($this->getError() == null){return true;}
        return false;
    }




}