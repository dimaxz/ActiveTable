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

class DeleteEntity implements ActionInterface {

	protected $repository;
	protected $output;

	function __construct( CrudRepositoryInterface $repo, OutputInterface $output) {
		$this->repository 	= $repo;
		$this->output		= $output;
	}

	public function process() {
		if(!$model = $this->repository->findById((int)$_GET["id"])){
			$this->output->addContent(
				(new Message('Ошибка при удалении, удаление выбранной записи невозможно. Позиция не найдена.<br/>'))
					->setBack(trs('Вернуться к редактированию таблицы'))
					->setType("error")
					->render()
			);
			return;
		}

		try{

			$this->repository->delete($model);
		} catch (\Repository\Exceptions\ForeignKeyConstraint $ex) {

			$this->output->addContent(
				(new Message('Ошибка при удалении, удаление выбранной записи невозможно. Выбранная позиция используется другими справочниками.<br/>'))
					->setBack(trs('Вернуться к редактированию таблицы'))
					->setType("error")
					->render()
			);

			return false;

		} catch (\Repository\Exceptions\RepositoryException $ex) {

			$this->output->addContent(
				(new Message('Ошибка при удалении'))
					->setBack(trs('Вернуться к редактированию таблицы'))
					->setType("error")
					->render()
			);

			return false;

		}


		$this->output->addContent(
			(new Message('Запись удалена.'))
				->setBack(trs('Вернуться к редактированию таблицы'))
				->render()
		);
	}

}