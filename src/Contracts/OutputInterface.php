<?php

namespace ActiveTableEngine\Contracts;

/**
 * интерефйс по работы с выводом контента
 * @package ActiveTableEngine\Contracts
 */
interface OutputInterface {

	/**
	 * Добавление контента
	 * @param string $buffer
	 *
	 * @return mixed
	 */
	public function addContent(string $buffer);

	/**
	 * Получение контента
	 * @return string
	 */
	public function getContent(): string;

	/**
	 * @param array $data
	 *
	 * @return mixed
	 */
	public function setData(array $data);

	/**
	 * @return array
	 */
	public function getData():array;

}