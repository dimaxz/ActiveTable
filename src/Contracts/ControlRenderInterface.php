<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 24.01.2019
 * Time: 11:57
 */

namespace ActiveTable\Contracts;

/**
 * поведение контролов
 * Interface ControlRenderInterface
 * @package ActiveTable\Contracts
 */
interface ControlRenderInterface {

	public function render() : string;
}