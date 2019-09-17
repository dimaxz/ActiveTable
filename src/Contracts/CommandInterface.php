<?php


namespace ActiveTable\Contracts;

/**
 * поведение комманд
 * Interface CommandInterface
 * @package ActiveTable\Contracts
 */
interface CommandInterface
{
    public function process(): void;
}