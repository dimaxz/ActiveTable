<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 28.01.2019
 * Time: 15:13
 */

namespace src_\Concrete;

use Repo\CrudRepositoryInterface;
use Repo\PaginationInterface;
use src_\Contracts\PaginationRenderInterface;

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