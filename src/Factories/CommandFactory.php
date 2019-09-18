<?php


namespace ActiveTable\Factories;

//use ActiveTable\Commands\FormSubmit;
use ActiveTable\Commands\FormView;
use ActiveTable\Commands\TableView;
use ActiveTable\Contracts\CommandFactoryInterface;
use ActiveTable\Contracts\CommandInterface;
use ActiveTable\Contracts\OutputInterface;
use ActiveTable\EmptyControls\Form;
//use ActiveTable\EmptyControls\TableAction;
use ActiveTable\EmptyControls\TableBottomControl;
use ActiveTable\EmptyControls\TableControl;
//use ActiveTable\EmptyControls\TableFilter;
//use ActiveTable\EmptyControls\TableRowAction;
use ActiveTable\EmptyControls\TableTopControl;
use ActiveTable\Exceptions\ActiveTableException;
use ActiveTable\Commands\TableAction;

/**
 * Фабрика комманд
 * Class CommandFactory
 * @package ActiveTable\Factories
 */
class CommandFactory implements CommandFactoryInterface
{
    protected $events = [
        "TABLE_VIEW",
        "FORM_VIEW",
        "TABLE_ACTION"
    ];

    /**
     * @var string
     */
    protected $event;

    /**
     * CommandFactory constructor.
     * @param $event
     * @throws ActiveTableException
     */
    public function __construct(string $event = "TABLE_VIEW")
    {
        if(!in_array($event,$this->events)){
            throw new ActiveTableException("event not found");
        }
        $this->event = $event;
    }

    /**
     * Возвращаем комманды в зависимости от ситуации
     * @param OutputInterface $output
     * @return CommandInterface
     */
    public function build(OutputInterface $output): CommandInterface
    {
        switch ($this->event){
            case "TABLE_VIEW";

                //стандартное отображение таблицы
                return new TableView(
                    $output,
                    //new TableFilter(),
                    //new TableAction(),
                    //new TableRowAction(),
                    new TableTopControl(),
                    new TableControl(),
                    new TableBottomControl()
                );

            break;

            case "FORM_VIEW";

                //стандартное отображение таблицы
                return new FormView(
                    $output,
                    new Form()
                );

                break;

            case "TABLE_ACTION";

                //стандартное отображение таблицы
                return new TableAction(
                    $output,
                    new TableControl()
                );

                break;
        }
    }

}