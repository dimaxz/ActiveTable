<?php


namespace ActiveTable\Commands;


use ActiveTable\Contracts\CommandInterface;
use ActiveTable\Contracts\OutputInterface;
use ActiveTable\Contracts\TableControlInterface;

/**
 * Действие над таблицей
 * Class TableAction
 * @package ActiveTable\Commands
 */
class TableAction implements CommandInterface
{
    protected $output;
    protected $tableControl;

    public function __construct(OutputInterface $output, TableControlInterface $tableControl)
    {
        $this->output = $output;
        $this->tableControl = $tableControl;
    }


    public function process(): void
    {
        $this->output->addContent(
            "<TABLE_ACTION_PROCESS>"
        );
    }


}