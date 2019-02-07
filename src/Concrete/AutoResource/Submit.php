<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 24.01.2019
 * Time: 13:13
 */

namespace ActiveTableEngine\Concrete\AutoResource;

use ActiveTableEngine\Contracts\FormControlRenderInterface;

class Submit implements  FormControlRenderInterface {

	use ControlRender;

	public function __construct($name, $text = null, $width = null, $disabled = false, $visible = true) {
		$this->object = new \Submit($name, $text, $width, $disabled, $visible);
	}

}