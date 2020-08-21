<?php


namespace ActiveTable\Contracts;


interface FormInterface extends ControlRenderInterface
{

    public function isValid():bool ;

}