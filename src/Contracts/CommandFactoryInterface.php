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
     * @param DataTableEngine $output
     * @return CommandInterface
     */
    public function build(DataTableEngine $output): CommandInterface;

    /**
     * @return string
     */
    public function getEventName(): string;

}