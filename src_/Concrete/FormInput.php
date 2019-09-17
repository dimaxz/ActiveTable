<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 11.05.2019
 * Time: 15:08
 */

namespace src_\Concrete;


use src_\Contracts\FormControlRenderInterface;

class FormInput implements FormControlRenderInterface
{

    protected $name;

    protected $value;

    protected $width;

    /**
     * FormInput constructor.
     */
    public function __construct($name)
    {

        $this->name = $name;
    }


    /**
     * @return mixed
     */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return FormInput
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return FormInput
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     * @return FormInput
     */
    public function setWidth(int $width)
    {
        $this->width = $width;
        return $this;
    }


    public function render(): string
    {
     return sprintf("<input name='%s' value='%s' style='width: %s px' />",$this->name,$this->value,$this->width);
    }

}