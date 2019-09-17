<?

namespace  src_\Exceptions;

/**
 * Description of ActionError
 *
 * @author d.lanec
 */
class ActionError extends \Exception {
	
	protected $actionName;
	
	function __construct($actionName) {
		$this->actionName = $actionName;
		parent::__construct(sprintf("action %s error", $actionName));
	}
	
	function getActionName(){
		return $this->actionName;
	}
}
