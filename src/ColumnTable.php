<?php

namespace ActiveTable;

/**
 * Колонка
 * Class ColumnTable
 * @package ActiveTable
 */
class ColumnTable {

	protected $format = [];
	protected $sorted = true;
	protected $name;
	protected $caption;
	protected $exported = true;
    /**
     * @var int|null
     */
	protected $width;
    /**
     * @var int|null
     */
	protected $widthPercent;

	public function __construct($name, $caption) {
		$this->name = $name;
		$this->caption = $caption;
	}

    /**
     * @return int|null
     */
    public function getWidth(): ?string
    {
        return $this->width > 0 || $this->widthPercent > 0 ?
            ($this->width > 0 ? $this->width.'px': $this->widthPercent .'%'):
            null;
    }

    /**
     * @param int|null $width
     * @return ColumnTable
     */
    public function setWidth(?int $width, bool  $percent = false): ColumnTable
    {
        if($percent===false) {
            $this->width = $width;
        }
        else{
            $this->widthPercent = $width;
        }

        return $this;
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