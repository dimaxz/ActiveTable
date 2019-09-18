<?php


namespace ActiveTable\EmptyControls;


use ActiveTable\Contracts\TableControlInterface;

class TableControl implements TableControlInterface
{
    public function render(): string
    {
        return "<TABLE_HTML>";
    }

}