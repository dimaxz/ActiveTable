<?

namespace ActiveTableEngine\Concrete;

/**
 * Обработчик команд
 *
 * @author d.lanec
 */
class ActionHandler  {

	protected $actions = [];

	/**
	 * вызываем действие
	 * @param $name
	 *
	 * @return bool
	 * @throws \ActiveTableEngine\Exceptions\ActionError
	 */
	public function call($name){
		foreach ($this->getActions($name) as $action) {

			$action->process();
//			if(!$action->process()){
//				throw new \ActiveTableEngine\Exceptions\ActionError($name);
//			}
		}
	}

	/**
	 * @param                                              $name
	 * @param \ActiveTableEngine\Contracts\ActionInterface $action
	 *
	 * @return $this
	 */
	public function add($name, \ActiveTableEngine\Contracts\ActionInterface $action ){
		$this->actions[$name][]= $action;
		return $this;
	}

	/**
	 * дОбавление события в первую очередь
	 *
	 * @param                                              $name
	 * @param \ActiveTableEngine\Contracts\ActionInterface $action
	 *
	 * @return $this
	 */
	public function addFirst($name,  \ActiveTableEngine\Contracts\ActionInterface $action ) {
		$this->setActions(
				$name,
				array_merge( 
						[$action] , 
					$this->getActions($name)
						)
				);
		return $this;		
	}

	/**
	 * @param $name
	 *
	 * @return array
	 */
	public function getActions($name):array {
		return (array)$this->actions[$name];
	}

	/**
	 * @param $name
	 * @param $data
	 *
	 * @return $this
	 */
	public function setActions($name,$data) {
		$this->actions[$name] = $data;
		return $this;
	}
	
}
