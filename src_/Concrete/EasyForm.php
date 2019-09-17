<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 23.01.2019
 * Time: 17:43
 */

namespace src_\Concrete;

use src_\Contracts\FormRenderingInterface;
use src_\Contracts\TableActionInterface;

class EasyForm implements FormRenderingInterface {

	protected $baseUrl;
	protected $name;
	protected $class = "easy_form";

	public function __construct($name, TableActionInterface $actionRequest) {
		$this->name = $name;
		$this->baseUrl = $actionRequest->getBaseUrl();
	}

	/**
	 * @param string $class
	 *
	 * @return $this
	 */
	public function setClass(string $class){
		$this->class = $class;
		return $this;
	}

	/**
	 * @return string
	 */
	public function renderHeader(): string {
		return sprintf("<form name='%s' class='%s' type='submit' method='post'>",$this->name,$this->class);
	}

	/**
	 * @param array $fields
	 *
	 * @return string
	 */
	public function renderBody(array $fields): string {
		$html = "<table>";
		foreach($fields as $name => $field){
			$html .="<tr><td><label for='$name'>{$field[0]}</label></td><td>{$field[1]}</td></tr>";
		}
		$html .= "</table>";
		return $html;
	}

	/**
	 * @return string
	 */
	public function renderBottom(): string {
		return "</form>";
	}

}