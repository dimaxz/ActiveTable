<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 28.01.2019
 * Time: 15:13
 */

namespace ActiveTableEngine\Concrete;

use ActiveTableEngine\Contracts\CrudRepositoryInterface;
use ActiveTableEngine\Contracts\PaginationInterface;
use ActiveTableEngine\Contracts\PaginationRenderInterface;

class EasyPagination implements PaginationRenderInterface {

	protected $repo;
	protected $action;
	protected $criteria;

	public function __construct(CrudRepositoryInterface $repo,  PaginationInterface $action) {
		$this->repo 	= $repo;
		$this->action 	= $action;
	}

	public function render(): string {

		return sprintf("Всего записей: %s, показано: %s",$this->repo->count(),$this->action->getDefaultLimit());
	}

}