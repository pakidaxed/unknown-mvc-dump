<?php

namespace Ca\Framework\Helper;

class FormBuilder
{
    private $form = '';


    public function __construct($method, $action, $class = '', $id = '')
    {
        $this->form .= "<form method='$method' action='$action' class='$class' id='$id'>";
        return $this;
    }

    public function input($type, $name, $class = '', $id = '', $placeholder = '', $label = '', $wrap = '', $value= '', $checked = false)
    {
        if ($label !== '' && $id !== '') {
            $this->form .= "<label for='$id'>$label</label>";
        }
        $checked ? $check = 'checked' : $check = '';
        $this->form .= "<input type='$type' name='$name' class='$class' id='$id' placeholder='$placeholder' value='$value' $check ><br>";

        return $this;
    }

    public function select($name, $options, $label)
    {
        if ($label !== '') {
            $this->form .= "<label for ='$name'>$label</label>";
        }
        $this->form .= "<select name='$name'>";
        foreach ($options as $id => $option) {
            $this->form .= "<option value='$id'>$option</option>";
        }
        $this->form .= "</select><br />";
        return $this;
    }

    //'description','describe your self', 'textarea-input'
    public function textarea($name, $placeholder, $class='', $value = '')
    {
        $this->form .= "<textarea name='$name' class='$class' placeholder='$placeholder'>$value</textarea>";
        return $this;
    }


    public function button($name, $text, $class = '')
    {
        $this->form .= "<button name='$name' class='$class'>$text</button><br>";
        return $this;
    }

    public function get()
    {
        return $this->form . '</form>';
    }


}