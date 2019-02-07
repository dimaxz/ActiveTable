<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 22.01.2019
 * Time: 16:47
 */

namespace ActiveTableEngine\Concrete;

use ActiveTableEngine\Contracts\PaginationInterface;

class Navigation implements PaginationInterface {

	protected $page = 0;

	protected $limit = 0;

	protected $def = 10;

	public function getPage(): int {
		return $this->page;
	}

	public function getLimit(): int {
		return $this->limit;
	}

	public function setPage(int $page) {
		$this->page = $page;
		return $this;
	}

	public function setLimit(int $limit) {
		$this->limit = $limit;
		return $this;
	}

	public function getDefaultLimit(): int {
		return $this->def;
	}

}