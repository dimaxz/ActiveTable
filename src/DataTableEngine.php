<?php

namespace ActiveTable;

use ActiveTable\Contracts\ControlRenderInterface;
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

    function __construct(CrudRepositoryInterface $repo, string $name)
    {
        $this->repo = $repo;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function render() : string{

        $command
            = (new Commands\TableView())
            ->process();


        return "dfesjbhjsk";
    }

}