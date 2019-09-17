<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 22.01.2019
 * Time: 17:01
 */

namespace src_\Contracts;

interface TableRenderingInterface {

	/**
	 * рендерит тело таблицы
	 *
	 * @param array $rows готовые для отображения данные
	 *
	 * @return mixed
	 */
	public function renderBody(array $rows) : string;

	public function renderHeader(array $rows) : string;

	public function renderTop(array $rows) : string;

	public function renderBottom() : string;

}