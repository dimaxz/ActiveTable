<?php


namespace ActiveTable\Commands;


use ActiveTable\Contracts\CommandInterface;
use ActiveTable\Contracts\OutputInterface;
use ActiveTable\Contracts\TableFilterInterface;

/**
 * Комманда для просмотра фильтра + действия + таблица + навигация
 * Class TableView
 * @package ActiveTable\Commands
 */
class TableView implements CommandInterface
{

    protected $filter;
    protected $output;

    public function __construct(OutputInterface $output, TableFilterInterface $filter)
    {
        $this->output = $output;
        $this->filter = $filter;
    }

    public function process(): void
    {

        $this->output->addContent($this->filter->render());

    }

}