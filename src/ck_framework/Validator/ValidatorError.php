<?php


namespace ck_framework\Validator;


class ValidatorError
{
    private $messages = [
        'required' => 'the field "%s" is required',
        'empty' => 'the field "%s" is not empty',
        'isNotSlug' => 'the field "%s" is not valid slug',
        'minLength' => 'the field "%s" must contain a minimum of %d character',
        'maxLength' => 'the field "%s" must contain a maximum  of %d character',
        'length' => 'the field "%s" must contain a number of characters included in between %d and %d'
    ];
    /**
     * @var string
     */
    private $value;
    /**
     * @var array
     */
    private $attributes;
    /**
     * @var string
     */
    private $key;

    /**
     * ValidatorError constructor.
     * @param string $key
     * @param string $value
     * @param array $attributes
     */
    public function __construct(string $key ,string $value, array $attributes = [])
    {
        $this->value = $value;
        $this->attributes = $attributes;
        $this->key = $key;
    }

    /**
     * build string message
     * @return string
     */
    public function __toString()
    {
        $params = array_merge([$this->messages[$this->value], $this->key], $this->attributes);
        return (string)call_user_func_array('sprintf', $params);
    }
}