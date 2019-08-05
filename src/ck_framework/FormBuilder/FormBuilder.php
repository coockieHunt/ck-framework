<?php


namespace ck_framework\FormBuilder;


use ck_framework\Utils\SnippetUtils;

class FormBuilder
{
    /**
     * @var string
     */
    private $method;
    /**
     * @var string
     */
    private $action;
    /**
     * @var array
     */
    private $form = [];
    /**
     * @var array|null
     */
    private $value;

    /**
     * FormBuilder constructor.
     * @param string $action
     * @param string $method
     * @param array|null $value
     */
    public function __construct(string $action, string $method = 'POST', ?array $value = [])
    {
        $this->method = $method;
        $this->action = $action;
        $this->value = $value;
    }

    public function text(string $name, ?string $label = null, ?array $args = []) :self {
        $value = $this->getValue($name);
        $form = $this->buildInput( 'text', $name,  $value , $label , $args);
        $this->setForm($form);

        return $this;
    }

    public function password(string $name, ?string $label = null, ?array $args = []) :self {
        $value = $this->getValue($name);
        $form = $this->buildInput( 'password', $name,  $value , $label , $args);
        $this->setForm($form);
        return $this;
    }

    public function email(string $name, ?string $label = null, ?array $args = []) :self {
        $value = $this->getValue($name);
        $form = $this->buildInput( 'email', $name,  $value , $label , $args);
        $this->setForm($form);

        return $this;
    }

    public function build(): array{
        $input = array_reverse($this->form);
        $start = '<form action="'. $this->action . '" method="'. $this->method . '">';
        array_unshift($input, $start);
        $input[] = '<form/>';
        return $input;
    }

    /**
     * @param mixed $form
     */
    private function setForm($form): void
    {
        if (gettype($form)  == 'array'){
            $this->form = array_merge($form, $this->form);
        }else{
            $this->form[] = $form;
        }
    }

    private function buildInput(string $type, string $name, ?string $value = '' , ?string $label = null , ?array $args = []){

        $output = SnippetUtils::ArrayArgsToHtml($args);
        $form = sprintf('<input type="%s" value="%s" name="%s" %s/>', $type, $value, $name, $output);
        if ($label == null){
            return $form;
        }else{
            $build = [
                '<label for="'. $name .'">',
                $label,
                $form,
                '</label> ',
            ];
            return array_reverse($build);
        }
    }

    private function getValue(string $value){
        if(isset($this->value[$value])){return $this->value[$value];};
        return null;
    }

}