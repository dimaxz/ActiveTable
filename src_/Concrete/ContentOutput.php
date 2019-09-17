<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 25.01.2019
 * Time: 9:12
 */

namespace src_\Concrete;

use src_\Contracts\OutputInterface;

/**
 * Простая реализация вывода
 * @package ActiveTableEngine\Concrete
 */
class ContentOutput implements OutputInterface {

	protected $content = "";
	protected $data = [];

	/**
	 * @param string $buffer
	 *
	 * @return $this|mixed
	 */
	public function addContent(string $buffer) {
		$this->content .= $buffer;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getContent(): string {
		return $this->content;
	}

	/**
	 * @param array $data
	 *
	 * @return $this
	 */
	public function setData(array $data){
		$this->data = $data;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getData():array{
		return $this->data;
	}
}