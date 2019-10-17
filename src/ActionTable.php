<?php


namespace ActiveTable;

use ActiveTable\Contracts\FormControlRenderInterface;

class ActionTable
{
    /**
     * @var FormControlRenderInterface
     */
    protected $control;

    /**
     * @var array
     */
    protected $calback;

    protected $caption;

    function __construct(FormControlRenderInterface $control,array $calback)
    {
        $this->control = $control;
        $this->calback = $calback;
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
     * @return ActionTable
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @return FormControlRenderInterface
     */
    public function getControl(): FormControlRenderInterface
    {
        return $this->control;
    }

    /**
     * @param FormControlRenderInterface $control
     * @return ActionTable
     */
    public function setControl(FormControlRenderInterface $control): ActionTable
    {
        $this->control = $control;
        return $this;
    }

    /**
     * @return array
     */
    public function getCalback(): array
    {
        return $this->calback;
    }

    /**
     * @param array $calback
     * @return ActionTable
     */
    public function setCalback(array $calback): ActionTable
    {
        $this->calback = $calback;
        return $this;
    }


}