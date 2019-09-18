<?php

namespace ActiveTable;

use ActiveTable\Contracts\CommandFactoryInterface;
use ActiveTable\Contracts\ControlRenderInterface;
use ActiveTable\EmptyControls\Content;
use ActiveTable\EmptyControls\TableAction;
use ActiveTable\EmptyControls\TableBottomControl;
use ActiveTable\EmptyControls\TableControl;
use ActiveTable\EmptyControls\TableFilter;
use ActiveTable\EmptyControls\TableRowAction;
use ActiveTable\EmptyControls\TableTopControl;
use Repo\CrudRepositoryInterface;

class DataTableEngine implements ControlRenderInterface
{

    /**
     * @var CrudRepositoryInterface
     */
    protected $repo;

    /**
     * @var string
     */
    protected $name;

    protected $commandFactory;

    function __construct(CrudRepositoryInterface $repo, string $name, CommandFactoryInterface $commandFactory)
    {
        $this->repo = $repo;
        $this->name = $name;
        $this->commandFactory = $commandFactory;
    }

    /**
     * @return string
     */
    public function render() : string{

        $output = new Content();

        $this->commandFactory->build($output)->process();

        return $output->getContent();
    }

}