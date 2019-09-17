<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 23.01.2019
 * Time: 17:25
 */

namespace src_\Contracts;

interface FormRenderingInterface {

	public function renderHeader(): string;
	public function renderBody(array $rows): string;
	public function renderBottom(): string;
}