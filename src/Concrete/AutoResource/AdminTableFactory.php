<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 29.01.2019
 * Time: 10:43
 */

namespace ActiveTableEngine\Concrete\AutoResource;

use ActiveTableEngine\Concrete\ColumnTable;
use ActiveTableEngine\Concrete\Commands\CreateEntity;
use ActiveTableEngine\Concrete\Commands\DeleteEntity;
use ActiveTableEngine\Concrete\Commands\LoadPostData;
use ActiveTableEngine\Concrete\Commands\UpdateEntity;
use ActiveTableEngine\Concrete\ContentOutput;

use ActiveTableEngine\Concrete\EasyPagination;

use ActiveTableEngine\Concrete\TableAction;
use ActiveTableEngine\Contracts\CrudRepositoryInterface;
use ActiveTableEngine\EventName;
use Infrastructure\Repositories\AbstractCriteria;

class AdminTableFactory {

	/**
	 * @param CrudRepositoryInterface $repo
	 * @param                         $name
	 *
	 * @return \ActiveTableEngine\DataTableSimple
	 */
	public static function create(CrudRepositoryInterface $repo,AbstractCriteria $criteria, $name) {

		$baseUrl = rtrim( str_replace(  $_SERVER["QUERY_STRING"],"",$_SERVER["REQUEST_URI"] ) , "?");
		$content = new ContentOutput();
		$action = new TableAction();
		$table
			= (new DataTable("orders", $action))
			->setPagination(new Pagination($repo, $criteria))
			->setClass("admin_edit_table")
		;
		$form
			= new DataForm($name,$action)
		;

		$formatter = new TableFormatterBase($baseUrl);

		$repoTable = (new \ActiveTableEngine\DataTableSimple($repo, $name));

		return $repoTable
			->setSearchCriteria($criteria)
			->setOutputContent($content)
			->setTableDriver($table)
			->setFormDriver($form)

			//style
			->setWidthFields(500)
			//command
			->addActionCommand(EventName::ON_READ, new LoadPostData($repo, $content, $action))
			->addActionCommand(EventName::BEFORE_FORM_RENDER, new BuildFormButtons($repoTable,$baseUrl))
			->addActionCommand(EventName::ON_UPDATE, new UpdateEntity($repo, $content))
			->addActionCommand(EventName::ON_CREATE, new CreateEntity($repo, $content))
			->addActionCommand(EventName::ON_DELETE, new DeleteEntity($repo, $content))
			//columns
			->addColumn(
				(new ColumnTable("_edit", ""))->setFormat($formatter, "formatEdit")->setSorted(false)
			)
			->addColumn(
				(new ColumnTable("_del", ""))->setFormat($formatter, "formatDel")->setSorted(false)
			)
			;
	}

}