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

    public function build(DataTableEngine $output): CommandInterface;

}