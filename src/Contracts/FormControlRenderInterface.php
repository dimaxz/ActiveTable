<?php

namespace ActiveTable\Contracts;

interface FormControlRenderInterface extends ControlRenderInterface {

	public function getName(): string;

	public function setValue($value);

	public function setWidth(int $width);
}