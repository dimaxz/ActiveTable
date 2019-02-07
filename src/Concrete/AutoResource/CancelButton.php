<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 24.01.2019
 * Time: 13:15
 */

namespace ActiveTableEngine\Concrete\AutoResource;

use ActiveTableEngine\Contracts\FormControlRenderInterface;

class CancelButton implements FormControlRenderInterface {

	use ControlRender;

	public function __construct($name, $resetLink, $text = null, $width = null, $disabled = false, $visible = true) {
		$this->object = new \ResetButton($name, $resetLink, $text, $width, $disabled, $visible);
	}
}