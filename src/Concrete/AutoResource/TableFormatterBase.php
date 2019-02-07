<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 29.01.2019
 * Time: 10:55
 */

namespace ActiveTableEngine\Concrete\AutoResource;

use Mapper\AbstractEntity;

class TableFormatterBase {

	protected $baseUrl;

	function __construct(string $baseUrl) {

		$this->baseUrl = $baseUrl;
	}

	/**
	 * колонка редактирования
	 */
	public function formatEdit(\Mapper\AbstractEntity $entity) {


		$editLink = new \cLink($this->baseUrl, '');
		$editLink->addQueryParam('fn', "edit");

		$editLink->addQueryParam('id', $entity->getId());

		return sprintf('<a name="link" href="%s"><img src="/_sysimg/ar_edit.png" alt="редактировать" title="редактировать" border="0"></a>', $editLink->link);
	}

	/**
	 * колонка редактирования
	 */
	public function formatDel(\Mapper\AbstractEntity $entity) {

		$editLink = new \cLink($this->baseUrl, '');
		$editLink->addQueryParam('fn', "del");

		$editLink->addQueryParam('id', $entity->getId());

		return sprintf('<a name="link" href="%s" onclick="return confirm(\'Вы действительно хотите удалить данную запись?\')"><img src="/_sysimg/ar_delete.png" alt="Удалить" title="Удалить" border="0"></a>', $editLink->link);
	}

}