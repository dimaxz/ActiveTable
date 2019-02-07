<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 25.01.2019
 * Time: 15:56
 */

namespace ActiveTableEngine\Concrete\Commands;

use ActiveTableEngine\Contracts\ActionInterface;
use ActiveTableEngine\Contracts\CrudRepositoryInterface;
use ActiveTableEngine\Contracts\OutputInterface;
use Infrastructure\Repositories\ValidationException;

class CreateEntity implements ActionInterface {

	protected $repository;
	protected $content;

	function __construct( CrudRepositoryInterface $repo, OutputInterface $content) {
		$this->repository 	= $repo;
		$this->content		= $content;
	}

	public function process() {

		$model = $this->repository->createEntity();

		foreach ($_POST as $name => $value){
			if(method_exists($model,"set".$name)){
				$model->{"set".$name}($value);
			}
		}

		try{
			$this->repository->save($model);
			$this->content->addContent("Успешно сохранено");
		}catch (ValidationException $ex){
			$this->content->addContent("Не заполнены обязательные поля");
		}

	}

}