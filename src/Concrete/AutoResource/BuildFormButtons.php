<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 29.01.2019
 * Time: 11:09
 */

namespace ActiveTableEngine\Concrete\AutoResource;

use ActiveTableEngine\Contracts\ActionInterface;
use ActiveTableEngine\DataTableSimple;

/**
 * Класс  достраивающий форму. кнопки управления
 * @package ActiveTableEngine\Concrete\AutoResource
 */
class BuildFormButtons implements ActionInterface {

	protected $repoTable;
	protected $baseUrl;

	/**
	 * BuildFormButtons constructor.
	 *
	 * @param $repoTable
	 * @param $baseUrl
	 */
	public function __construct(DataTableSimple $repoTable,$baseUrl) {
		$this->repoTable = $repoTable;
		$this->baseUrl = $baseUrl;
	}

	/**
	 *
	 */
	public function process() {
		$this->repoTable
			->addField(new Submit("submit", "Подача запроса"))
			->addField(new CancelButton("cancel", $this->baseUrl, "Отменить"))
		;
	}

}