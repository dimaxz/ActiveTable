<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 24.01.2019
 * Time: 12:41
 */

namespace ActiveTableEngine\Contracts;

interface ColumnTableInterface {

	public function getName():string;

	public function isSorted(): bool;

	public function isExported() : bool;

	public function getFormat() : array;

	public function getCaption(): string;

	public function setCaption(string $caption);

	public function setFormat($class, $method);

	public function setExported(bool $ex);

	public function setSorted(bool $ex);

	public function setName(string $name);
}