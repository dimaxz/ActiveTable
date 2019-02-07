<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 06.02.2019
 * Time: 15:34
 */

namespace ActiveTableEngine\Concrete\AutoResource\Controls;

use ActiveTableEngine\Concrete\AutoResource\ControlRender;
use ActiveTableEngine\Contracts\FormControlRenderInterface;

class DropDownList implements FormControlRenderInterface {

	use ControlRender;

	public function __construct($name,array $listValues, $defaultValues = null, $width = null, $disabled = false, $visible = true) {

		$this->object = new \DropDownList($name,$listValues, $defaultValues, $width, $disabled, $visible);
	}

	public function setValue($value){

		$this->object->value = (array)$value;
	}
}