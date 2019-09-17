<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 24.01.2019
 * Time: 12:46
 */

namespace src_\Concrete;

use src_\Contracts\ColumnTableInterface;

class ColumnTable implements ColumnTableInterface {

	protected $format = [];
	protected $sorted = true;
	protected $name;
	protected $caption;
	protected $exported = true;

	function __construct($name, $caption) {
		$this->name = $name;
		$this->caption = $caption;
	}

	public function setName(string $name) {

		$this->name = $name;
		return $this;
	}

	public function getName(): string {

		return $this->name;
	}

	public function isSorted(): bool {

		return $this->sorted;
	}

	public function isExported(): bool {

		return $this->exported;
	}

	public function getFormat(): array {

		return $this->format;
	}

	public function getCaption(): string {

		return $this->caption;
	}

	public function setExported(bool $ex): ColumnTable {

		$this->exported = $ex;

		return $this;
	}

	public function setSorted(bool $sorted): ColumnTable {

		$this->sorted = $sorted;

		return $this;
	}

	/**
	 * @param $class
	 * @param $method
	 *
	 * @return ColumnTable
	 */
	public function setFormat($class, $method): ColumnTable {

		$this->format = [$class, $method];

		return $this;
	}

	/**
	 * @param string $caption
	 *
	 * @return $this
	 */
	public function setCaption(string $caption) {

		$this->caption = $caption;

		return $this;
	}

}