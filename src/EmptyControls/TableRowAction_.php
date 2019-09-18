<?php


namespace ActiveTable\EmptyControls;


use ActiveTable\Contracts\TableRowActionInterface;

class TableRowAction implements TableRowActionInterface
{
    public function render(): string
    {
        return "<TABLE_ROW_ACTION_HTML>";
    }

}