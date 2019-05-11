<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 25.01.2019
 * Time: 15:56
 */

namespace ActiveTableEngine\Concrete\Commands;

use ActiveTableEngine\Concrete\AutoResource\Message;
use ActiveTableEngine\Contracts\ActionInterface;
use Repo\CrudRepositoryInterface;
use ActiveTableEngine\Contracts\OutputInterface;
use Infrastructure\Repositories\ValidationException;

class UpdateEntity implements ActionInterface {

	protected $repository;
	protected $content;

	function __construct(CrudRepositoryInterface $repo, OutputInterface $content) {

		$this->repository = $repo;
		$this->content = $content;
	}

	public function process() {

		if (!$model = $this->repository->findById((int)$_GET["id"])) {
			return;
		}

		foreach ($_POST as $name => $value) {
			if (method_exists($model, "set" . $name)) {
				$model->{"set" . $name}($value);
			}
		}

		try {
			$this->repository->save($model);
			$this->content->addContent((new Message('Запись изменена.'))
				->setBack(trs('Вернуться к редактированию таблицы'))
				->render());
		} catch (ValidationException $ex) {
			$this->content->addContent((new Message(
				sprintf(
					"При изменении записи произошла ошибка: Поле " . $ex->getFirstError()
				)
			))
				->setType("error")
				->render());
		}

	}

}