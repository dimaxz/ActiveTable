<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Interface TestHelper
 */
trait TestHelper {

	/**
	 * получение защищенного свойства
	 *
	 * @param string $object
	 * @param string $propertyName
	 *
	 * @return ReflectionProperty
	 * @throws ReflectionException
	 */
	protected function getProtectedAttribute($object, $propertyName) {

		// создаем reflectionClass
		$reflectionClass = new \ReflectionClass($object);
		// получаем свойство
		$property = $reflectionClass->getProperty($propertyName);
		// делаем открытым
		$property->setAccessible(true);

		return $property;
	}

	/**
	 * Добавление значения в защищенный метод
	 *
	 * @param string $propertyName
	 * @param string $value
	 *
	 * @throws ReflectionException
	 */
	private function setValueprotectedProperty($propertyName, $value) {

		// создаем reflectionClass
		$reflectionClass = new \ReflectionClass($this->object);
		// получаем свойство
		$property = $reflectionClass->getProperty($propertyName);
		// делаем открытым
		$property->setAccessible(true);
		// изменяем значение
		$property->setValue($this->object, $value);
	}
}
