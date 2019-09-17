<?php

namespace ActiveTable\Contracts;

/**
 * интерефйс по работы с выводом контента
 * @package ActiveTableEngine\Contracts
 */
interface OutputInterface {

    /**
     * Добавление контента
     * @param string $buffer
     */
	public function addContent(string $buffer): void;

	/**
	 * Получение контента
	 * @return string
	 */
	public function getContent(): string;

    /**
     * Очистка
     */
	public function clear(): void;
}