<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 24.01.2019
 * Time: 12:53
 */

namespace ActiveTableEngine\Concrete\AutoResource;

/**
 * Добавляем поведение ВАР рендеринга
 * @package ActiveTableEngine\Concrete\AutoResource
 */
trait ControlRender {


	protected $object;

	/**
	 * @return string
	 */
	public function getName():string{
		return $this->object->name;
	}

	public function getObject(){
		return $this->object;
	}

	/**
	 * @return string
	 */
	public function render(): string {
		return $this->object->render(new \PHP_DataRender());
	}

	public function __call($name, $arguments) {

		return call_user_func_array([$this->object,$name],$arguments);
	}

	public function __set($name, $value) {
		$this->object->{$name} = $value;
	}

	public function __get($name) {
		return $this->object->{$name};
	}

	public function setValue($value){

		$this->object->value = $value;
	}

	public function setWidth(int $width){
		//$this->object->width = sprintf("%spx",$width);
		//$this->object->settings["style"] = sprintf("%s%spx",$this->object->settings["style"],$width);
		//$this->object->addAttribute("style", "width: $width");
		$this->object->setAttribute("style",sprintf("width:%spx",$width));
	}
}