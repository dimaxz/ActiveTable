<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 24.01.2019
 * Time: 11:57
 */

namespace src_\Contracts;

interface FormControlRenderInterface extends ControlRenderInterface {

	public function getName(): string;

	public function setValue($value);

	public function setWidth(int $width);
}