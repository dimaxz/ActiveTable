<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 24.01.2019
 * Time: 14:12
 */

namespace ActiveTableEngine\Concrete\AutoResource;
use ActiveTableEngine\Contracts\PaginationInterface;
use Zend\Diactoros\ServerRequest;

/**
 * Создает класс заполняя его данными из Request
 * Class SearchCriteriaFactory
 * @package Market\Factory
 */
class SearchCriteriaFactory {

	protected $request;

	function __construct(ServerRequest $request) {
		$this->request = $request;
	}

	/**
	 * создание криетрии поиска для репозитория
	 * @param ServerRequest $request
	 *
	 * @return \Infrastructure\Repositories\Order\OrderCriteria
	 */
	public function buildSearchCriteriaFromRequest(PaginationInterface $criteria):PaginationInterface{
		$data 		= $this->request->getQueryParams();
		$reflector = new \ReflectionClass($criteria);
		foreach($data as $param => $value){
			$match = $name = null;
			if(preg_match("~(.*)_by_(.*)~",$param,$match) && !empty($value)){
				$name = "set" . $match[1] . "By" . $match[2];
			}
			if(!method_exists($criteria, $name)){
				continue;
			}

			$type = $reflector->getMethod($name)->getParameters()[0]->getType();
			if($type && $type->getName()=="int"){
				$value = (int) $value;
			}

			if(
				(is_int($value) && $value > 0) ||
				(is_string($value) && $value > "")
			){
				$criteria->{$name}($value);
			}

		}

		$criteria
			->setPage(isset($data["page"])?$data["page"]:1)
			->setLimit(isset($data["rows"])?$data["rows"]:$criteria->getDefaultLimit())
		;

		return $criteria;
	}

}