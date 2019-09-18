<?php


namespace ActiveTable\Contracts;

/**
 * Фибрика для создания комманд
 * Interface CommandFactoryInterface
 * @package ActiveTable\Contracts
 */
interface CommandFactoryInterface
{

    public function build(OutputInterface $output): CommandInterface;

}