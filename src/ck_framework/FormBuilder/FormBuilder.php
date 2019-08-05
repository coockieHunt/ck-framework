<?php


namespace ck_framework\FormBuilder;


use ck_framework\Utils\SnippetUtils;
use Faker\Factory;
use Faker\Generator;

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
     * @var array|null
     */
    private $divArgs;
    /**
     * @var Generator
     */
    private $faker;

    /**
     * FormBuilder constructor.
     * @param string $action
     * @param string $method
     * @param array|null $divArgs
     */
    public function __construct(string $action, string $method = 'POST',?array $divArgs = [])
    {
        $this->method = $method;
        $this->action = $action;
        $this->divArgs = $divArgs;
        
        $this->faker = $faker = Factory::create();

    }

    /**
     * add text input
     * @param string $name
     * @param string|null $label
     * @param array|null $args
     * @return FormBuilder
     */
    public function text(string $name, ?string $label = null, ?array $args = []) :self {
        $value = $this->getValue($name);
        $form = $this->buildInput( 'text', $name,  $value , $label , $args);
        $this->setForm($form);

        return $this;
    }

    /**
     * add password input
     * @param string $name
     * @param string|null $label
     * @param array|null $args
     * @return FormBuilder
     */
    public function password(string $name, ?string $label = null, ?array $args = []) :self {
        $value = $this->getValue($name);
        $form = $this->buildInput( 'password', $name,  $value , $label , $args);
        $this->setForm($form);
        return $this;
    }

    /**
     * add email input
     * @param string $name
     * @param string|null $label
     * @param array|null $args
     * @return FormBuilder
     */
    public function email(string $name, ?string $label = null, ?array $args = []) :self {
        $value = $this->getValue($name);
        $form = $this->buildInput( 'email', $name,  $value , $label , $args);
        $this->setForm($form);

        return $this;
    }

    /**
     * add text area input
     * @param string $name
     * @param int $rows
     * @param string|null $label
     * @param array|null $args
     * @return FormBuilder
     */
    public function textarea(string $name, int $rows = 10, ?string $label = null, ?array $args = []) :self {
        $value = $this->getValue($name);
        $div = SnippetUtils::ArrayArgsToHtml($this->divArgs);
        $args = SnippetUtils::ArrayArgsToHtml($args);
        $placeHolder = $this->faker->text(2000);

        $form = sprintf('<textarea name="%s" rows="'. $rows .'" %s placeholder="%s">%s</textarea>', $name, $args, $placeHolder, $value);

        $build = [
            '<div '. $div .'>',
            '<label for="'. $name .'">' . $label . '</label> ',
            $form,
            '</div>',
        ];
        $this->setForm($build);

        return $this;
    }



    /**
     * build submit button
     * @param string $content
     * @param array|null $args
     * @return array
     */
    public function submit(string $content, ?array $args = []) {
        $args = SnippetUtils::ArrayArgsToHtml($args);

        $form = sprintf(  '<button type="submit" %s>', $args);

        $build = [
            $form,
            $content,
            '</button>'
        ];

        return $build;
    }

    /**
     * add content for input
     * example :
     * $form->text('slug', 'slug :', ['class' => 'form-control'])
     * $form->setArgs(['slug' => 'slug-here'])
     * display html : <input type="slug" value="slug-here" name="name" class="form-control">
     * @param array $args
     * @return FormBuilder
     */
    public function setArgs(array $args):self {
        $this->value = $args;
        return $this;
    }

    /**
     * build simple input
     * @param string $type
     * @param string $name
     * @param string|null $value
     * @param string|null $label
     * @param array|null $args
     * @return array|string
     */
    private function buildInput(string $type, string $name, ?string $value = '' , ?string $label = null , ?array $args = []){
        $div = SnippetUtils::ArrayArgsToHtml($this->divArgs);
        $output = SnippetUtils::ArrayArgsToHtml($args);
        $form = sprintf('<input type="%s" value="%s" name="%s" %s/>', $type, $value, $name, $output);
        if ($label == null){
            return $form;
        }else{
            $build = [
                '<div '. $div .'>',
                '<label for="'. $name .'">' . $label . '</label> ',
                $form,
                '</div>',
            ];
            return $build;
        }
    }

    /**
     * set form in $this->form
     * @param mixed $form
     */
    private function setForm($form): void
    {
        if (gettype($form)  == 'array'){
            $this->form =  array_merge($this->form, $form);
        }else{
            $this->form[] = $form;
        }
    }


    /**
     * get value
     * @param string $value
     * @return mixed|null
     */
    private function getValue(string $value){
        if(isset($this->value[$value])){return $this->value[$value];};
        return null;
    }

    /**
     * build form input
     * @return array
     */
    public function build(): array{
        return $this->form;
    }

    /**
     * initialize form
     * @return string
     */
    public function start(): string
    {
        return '<form action="'. $this->action . '" method="'. $this->method . '">';
    }

    /**
     * close form
     * @return string
     */
    public function end(): string
    {
        return '</form>';
    }
}