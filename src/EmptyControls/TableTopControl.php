<?php


namespace ActiveTable\EmptyControls;


use ActiveTable\Contracts\TableTopControlInterface;

class TableTopControl implements TableTopControlInterface
{
    public function render(): string
    {
        return "<TABLE_TOP_CONTROL_HTML>";
    }

}