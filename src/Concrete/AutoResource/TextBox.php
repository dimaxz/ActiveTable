<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 24.01.2019
 * Time: 12:53
 */

namespace ActiveTableEngine\Concrete\AutoResource;

use ActiveTableEngine\Contracts\FormControlRenderInterface;

/**
 * Декаратор
 *
 * @package ActiveTableEngine\Concrete\AutoResource
 */
class TextBox implements FormControlRenderInterface {

	use ControlRender;

	function __construct($name, $initValue = "", $width = null, $readonly = false, $disabled = false, $onkeypressScript = "", $visible = true, $id = false) {
		$this->object = new \TextBox($name, $initValue , $width , $readonly, $disabled, $onkeypressScript, $visible, $id );
	}

}