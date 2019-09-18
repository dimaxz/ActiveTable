<?php


namespace ActiveTable\Commands;


use ActiveTable\Contracts\CommandInterface;
use ActiveTable\Contracts\OutputInterface;
//use ActiveTable\Contracts\TableActionInterface;
use ActiveTable\Contracts\TableBottomControlInterface;
use ActiveTable\Contracts\TableControlInterface;
//use ActiveTable\Contracts\TableFilterInterface;
//use ActiveTable\Contracts\TableRowActionInterface;
use ActiveTable\Contracts\TableTopControlInterface;

/**
 * Комманда для просмотра фильтра + действия + таблица + навигация
 * Class TableView
 * @package ActiveTable\Commands
 */
class TableView implements CommandInterface
{

    protected $filter;
    protected $output;
    //protected $tableAction;
   // protected $tableRowAction;
    protected $tableTopControl;
    protected $tableControl;
    protected $tableBottomControl;

    public function __construct(OutputInterface $output,
                                //TableFilterInterface $filter,
             //             TableActionInterface $tableAction,
           //                     TableRowActionInterface $tableRowAction,
        TableTopControlInterface $tableTopControl,
                                TableControlInterface $tableControl,
                                TableBottomControlInterface $tableBottomControl)
    {
        $this->output = $output;
        //$this->filter = $filter;
      //  $this->tableAction = $tableAction;
       // $this->tableRowAction = $tableRowAction;
        $this->tableTopControl = $tableTopControl;
        $this->tableControl = $tableControl;
        $this->tableBottomControl = $tableBottomControl;
    }

    /**
     *
     */
    public function process(): void
    {
        //$this->output->addContent($this->filter->render());
        //$this->output->addContent($this->tableAction->render());
        //$this->output->addContent($this->tableRowAction->render());
        $this->output->addContent($this->tableTopControl->render());
        $this->output->addContent($this->tableControl->render());
        $this->output->addContent($this->tableBottomControl->render());
    }

}