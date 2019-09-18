<?php


namespace ActiveTable\EmptyControls;


use ActiveTable\Contracts\TableFilterInterface;

class TableFilter implements TableFilterInterface
{
    public function render(): string
    {
        return "<TABLE_FILTER_HTML>";
    }

}