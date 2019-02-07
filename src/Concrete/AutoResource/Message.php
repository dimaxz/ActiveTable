<?

namespace ActiveTableEngine\Concrete\AutoResource;


use ActiveTableEngine\Contracts\ControlRenderInterface;

/**
 * Description of Message
 *
 * @author d.lanec
 */
class Message implements ControlRenderInterface {
	
	protected $message;

	protected $back;
	
	protected $type = "notice";
			
	function __construct($message) {
		$this->message = $message;
	}

	public function render():string {

		global $_SYSTEM;

		return sprintf(
				'<div class="%s" >%s %s</div>',
				$this->type,
				$this->message,
				!empty($this->back)?'<a href="' . $_SYSTEM->REQUESTED_PAGE . '">' . $this->back . '</a>':''
				);
	}
	
	function getMessage() {
		return $this->message;
	}

	function getBack() {
		return $this->back;
	}

	function getType() {
		return $this->type;
	}

	function setMessage($message) {
		$this->message = $message;
		return $this;
	}

	function setBack($back) {
		$this->back = $back;
		return $this;
	}

	function setType($type) {
		$this->type = $type;
		return $this;
	}



	
}
