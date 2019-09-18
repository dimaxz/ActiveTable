<?php


namespace ActiveTable\EmptyControls;

/**
 *
 * Class TableAction
 * @package ActiveTable\EmptyControls
 */
class TableAction implements \ActiveTable\Contracts\TableActionInterface
{
    public function render(): string
    {
        return "<TABLE_ACTION_HTML>";
    }

}