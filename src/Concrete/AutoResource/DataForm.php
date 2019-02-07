<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 29.01.2019
 * Time: 13:57
 */

namespace ActiveTableEngine\Concrete\AutoResource;

use ActiveTableEngine\Contracts\FormRenderingInterface;
use ActiveTableEngine\Contracts\TableActionInterface;

class DataForm implements FormRenderingInterface {

	protected $baseUrl;
	protected $name;
	protected $class = "easy_form";

	public function __construct($name, TableActionInterface  $actionRequest) {

		$this->name = $name;
		$this->baseUrl = $actionRequest->getBaseUrl();
	}

	public function renderHeader(): string {

		return "";
	}



	/**
	 * @param array $fields
	 *
	 * @return string
	 */
	public function renderBody(array $fields): string {

		$form = new \CustomForm($this->class, $_SERVER['REQUEST_URI'], "POST");
		$html = '<table class="admin_edit_table" cellspacing="1" cellpadding="3"><tbody>';
		foreach ($fields as $name => $field) {

			$form->bindField($field[2]->getObject(),$field[0],$field[3]);

			if(is_a($field[2],Submit::class) || is_a($field[2],CancelButton::class)){
				$html .= '<tr><td></td><td><%FormControl:' . $name . '%></td></tr>';
			}
			else{
				$html .= '<tr><td><%FormControl:' . $name . '@caption%></td><td><%FormControl:' . $name . '%></td></tr>';
			}


		}
		$html .= '</tbody></table>';

		$form->setStyle($html);

		return $form->render(new \HTML_DataRender());
	}

	public function renderBottom(): string {

		return "";
	}

}