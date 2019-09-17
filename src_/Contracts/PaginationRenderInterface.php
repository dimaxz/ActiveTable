<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 28.01.2019
 * Time: 15:21
 */

namespace src_\Contracts;

interface PaginationRenderInterface {

	/**
	 * main render
	 * @return string
	 */
	public function render():string;

}