<?php


namespace ActiveTable;


use ActiveTable\Contracts\ControlRenderInterface;

class FilterField
{
    /**
     * @var ControlRenderInterface
     */
    protected $control;


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