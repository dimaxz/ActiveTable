<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 28.01.2019
 * Time: 15:13
 */

namespace src_\Concrete;

use src_\Contracts\PaginationRenderInterface;

class EmptyPagination implements PaginationRenderInterface {

	public function render(): string {
		return "";
	}

}