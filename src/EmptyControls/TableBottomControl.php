<?php


namespace ActiveTable\EmptyControls;


use ActiveTable\Contracts\TableBottomControlInterface;

class TableBottomControl implements TableBottomControlInterface
{
    public function render(): string
    {
        return "<TABLE_BOTTOM_CONTROL_HTML>";
    }

}