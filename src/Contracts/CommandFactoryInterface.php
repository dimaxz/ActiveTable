<?php

namespace ActiveTable\Contracts;

use ActiveTable\DataTableEngine;

/**
 * Фибрика для создания комманд
 * Interface CommandFactoryInterface
 * @package ActiveTable\Contracts
 */
interface CommandFactoryInterface
{

    /**
     * @param OutputInterface $output
     * @return CommandInterface
     */
    public function build(DataTableEngine $tableEngine): CommandInterface;

    /**
     * @return string
     */
    public function getEventName(): string;

}