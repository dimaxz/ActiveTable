<?php


namespace ActiveTable\EmptyControls;


use ActiveTable\Contracts\FormInterface;

class Form implements  FormInterface
{
    public function render(): string
    {
        return "<FORM_VIEW_HTML>";
    }

}