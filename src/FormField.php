<?php


namespace ActiveTable;


use ActiveTable\Contracts\ControlRenderInterface;

class FormField
{
    /**
     * @var ControlRenderInterface
     */
    protected $control;

    protected $require = false;

    protected $caption;

    function __construct(ControlRenderInterface $control)
    {
        $this->control = $control;
    }

    /**
     * @return ControlRenderInterface
     */
    public function getControl(): ControlRenderInterface
    {
        return $this->control;
    }

    /**
     * @param ControlRenderInterface $control
     * @return FormField
     */
    public function setControl(ControlRenderInterface $control): FormField
    {
        $this->control = $control;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRequire(): bool
    {
        return $this->require;
    }

    /**
     * @param bool $require
     * @return FormField
     */
    public function setRequire(bool $require): FormField
    {
        $this->require = $require;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param mixed $caption
     * @return FormField
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
        return $this;
    }


}