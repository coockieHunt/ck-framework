<?php


namespace ck_framework\FormBuilder;


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
        $form = '<input type="text" value="' . $value. '" name="' . $name. '"' . implode($args) . '/>';
        if ($label == null){
            $this->setForm($form);
        }else{
            $build = [
                '<p>',
                '<label>',
                $label,
                '</label> ',
                $form,
                '<p>'
            ];
            $this->setForm($build);
        }

        return $this;
    }

    public function password(string $name, ?string $label = null, ?array $args = []) :self {
        $value = $this->getValue($name);
        $form = '<input type="password" value="' . $value. '" name="' . $name. '"' . implode($args) . '/>';
         if ($label == null){
             $this->setForm($form);
         }else{
             $build = [
                 '<p>',
                 '<label>',
                 $label,
                 '</label> ',
                 $form,
                 '<p>'
             ];
             $this->setForm($build);
         }

        return $this;
    }

    public function email(string $name, ?string $label = null, ?array $args = []) :self {
        $value = $this->getValue($name);
        $form = '<input type="email" value="' . $value. '" name="' . $name. '"' . implode($args) . '/>';
        if ($label == null){
            $this->setForm($form);
        }else{
            $build = [
                '<p>',
                '<label>',
                $label,
                '</label> ',
                $form,
                '<p>'
            ];
            $this->setForm($build);
        }

        return $this;
    }

    public function build(){
        $start = '<form action="'. $this->action . '" method="'. $this->method . '">';
        array_unshift($this->form, $start);
        $this->setForm('</form>');
        foreach ($this->form as $element){
            echo htmlspecialchars_decode($element) ;
        }
        dd();
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

    private function getValue(string $value){
        if(isset($this->value[$value])){return $this->value[$value];};
        return null;
    }

}