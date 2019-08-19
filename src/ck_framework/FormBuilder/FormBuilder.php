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
    public function __construct( $action, $method = 'POST',?array $divArgs = [])
    {
        $this->method = $method;
        $this->action = $action;
        $this->divArgs = $divArgs;
        
        $this->faker = $faker = Factory::create();

    }

    /**
     * add text input
     * @param string $name
     * @param string|null $placeHolder
     * @param string|null $label
     * @param array|null $args
     * @return FormBuilder
     */
    public function text(string $name ,?string $placeHolder ='' ,?string $label = null, ?array $args = []) :self {
        $value = $this->getValue($name);
        $form = $this->buildInput( 'text', $name, $placeHolder,  $value , $label , $args);
        $this->setForm($form);

        return $this;
    }

    /**
     * add password input
     * @param string $name
     * @param string|null $placeHolder
     * @param string|null $label
     * @param array|null $args
     * @return FormBuilder
     */
    public function password(string $name, ?string $placeHolder ='', ?string $label = null, ?array $args = []) :self {
        $value = $this->getValue($name);
        $form = $this->buildInput( 'password', $name, $placeHolder,  $value , $label , $args);
        $this->setForm($form);
        return $this;
    }

    /**
     * add email input
     * @param string $name
     * @param string|null $placeHolder
     * @param string|null $label
     * @param array|null $args
     * @return FormBuilder
     */
    public function email(string $name ,?string $placeHolder ='' , ?string $label = null, ?array $args = []) :self {
        $value = $this->getValue($name);
        $form = $this->buildInput( 'email', $name,$placeHolder ,  $value , $label , $args);
        $this->setForm($form);

        return $this;
    }

    /**
     * add checkbox input
     * @param string $name
     * @param array|null $divArgs
     * @param boolean|null $checked
     * @param string|null $label
     * @param array|null $args
     * @return FormBuilder
     */
    public function checkbox(
        string $name ,
        ?array $divArgs =  [],
        ?bool $checked = false ,
        ?string $label = null,
        ?array $args = []
    ) :self {
        $value = $this->getValue($name);
        $div = SnippetUtils::ArrayArgsToHtml($divArgs);
        $formArgs = SnippetUtils::ArrayArgsToHtml($args);
        $divForm = '<div %s>';
        $divForm = sprintf($divForm, $div);

        if (gettype($value) != 'boolean'){$value = $checked;}
        if ($value){$value = 'checked';};

        $form = '<input type="checkbox" name="%s" %s %s>';
        $form = sprintf($form, $name, $formArgs, $value);

        $formLabel = '<label >%s</label>';
        $formLabel = sprintf($formLabel, ucfirst($label));

        $build = [
            $divForm,
            $form,
            $formLabel,
            '</div>'
        ];


        $this->setForm($build);

        return $this;
    }

    /**
     * add title category
     * @param string $html
     * @param string $content
     * @param array|null $args
     * @return FormBuilder
     */
    public function addCategory(string $html, string $content, ?array $args = []) :self {
        $args = SnippetUtils::ArrayArgsToHtml($args);

        $form = sprintf(  '<%s %s>%s<%s>', $html, $args, $content , '/' . $html);
        $this->setForm($form);
        return $this;
    }

    /**
     * add hidden input
     * @param string $name
     * @param string $value
     * @return FormBuilder
     */
    public function hidden(string $name, string $value):self {
        $form = sprintf(  '<input name="%s" type="hidden" value="%s">', $name, $value);
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
        $placeHolder = $this->faker->text(1500);

        $string = '<textarea name="%s" rows="'. $rows .'" %s placeholder="%s">%s</textarea>';
        $form = sprintf($string, $name, $args, $placeHolder, $value);

        $build = [
            '<div '. $div .'>',
            '<label for="'. $name .'">' . ucfirst($label) . '</label> ',
            $form,
            '</div>',
        ];
        $this->setForm($build);

        return $this;
    }

    public function select(string $name,array $option = [], ?string $label = null, ?array $args = []) :self {
        $defaultValue = $this->getValue($name);
        $div = SnippetUtils::ArrayArgsToHtml($this->divArgs);
        $args = SnippetUtils::ArrayArgsToHtml($args);

        $string = '<select id="exampleFormControlSelect2" %s name="%s">';
        $form = sprintf($string, $args, $name);

        $optionParse = '';
        foreach ($option as $key => $value){
            $selected = '';
            if ($defaultValue == $value or $defaultValue == $key){$selected = "selected='selected'";}
            $element = "<option value='{$key}' {$selected}>{$value}</option>";
            $optionParse = $optionParse . $element;
        }

        $build = [
            '<div '. $div .'>',
            '<label for="'. $name .'">' . ucfirst($label) . '</label> ',
            $form,
            $optionParse,
            '</select>',
            '</div>'
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

        $form = sprintf('<button type="submit" %s>', $args);

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
        $action =  'action="'. $this->action . '"';
        $method =  'method="'. $this->method . '"';

        if ($this->action === null){$action = '';};
        if ($this->method === null){$method = '';};

        return '<form '. $action . ' ' . $method . '>';
    }

    /**
     * close form
     * @return string
     */
    public function end(): string
    {
        return '</form>';
    }

    /**
     * build simple input
     * @param string $type
     * @param string $name
     * @param string|null $placeHolder
     * @param string|null $value
     * @param string|null $label
     * @param array|null $args
     * @return array|string
     */
    private function buildInput(
        string $type,
        string $name,
        ?string $placeHolder = '',
        ?string $value = '' ,
        ?string $label = null ,
        ?array $args = []
    ){
        $div = SnippetUtils::ArrayArgsToHtml($this->divArgs);
        $output = SnippetUtils::ArrayArgsToHtml($args);
        $form = sprintf('<input type="%s" value="%s" name="%s" placeholder="%s" %s/>', $type, $value, $name, $placeHolder, $output);
        if ($label == null){
            $build = [
                '<div '. $div .'>',
                $form,
                '</div>',
            ];
        }else{
            $build = [
                '<div '. $div .'>',
                '<label for="'. $name .'">' . ucfirst($label) . '</label> ',
                $form,
                '</div>',
            ];
        }
        return $build;
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
}