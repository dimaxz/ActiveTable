<?

namespace ActiveTableEngine\Concrete\AutoResource;

use ActiveTableEngine\Contracts\ControlRenderInterface;
use cLink;

/**
 * Кнопка добавления
 *
 * @author d.lanec
 */
class ButtonAdd implements ControlRenderInterface {
	
	protected $name;
	
	protected $icon;
			
	function __construct($name,$icon) {
		$this->name = $name;
		$this->icon = $icon;
	}
	
	function getIcon() {
		return $this->icon;
	}

	function setIcon($icon) {
		$this->icon = $icon;
		return $this;
	}

		
	function getName() {
		return $this->name;
	}

	function setName($name) {
		$this->name = $name;
		return $this;
	}


	public function render():string{
		
		$addRecord = new cLink(
			$_SERVER['REQUEST_URI'],
			$this->name
		);
			
		$addRecord->removeQueryParam("fn");
		$addRecord->removeQueryParam("id");
		$addRecord->addQueryParam("fn", "add");
		
		return sprintf(
				'<div style="text-align:center" align="top"><a name="link" href="%s"><img src="%s" alt="%s" title="%s" hspace="5" border="0" align="absmiddle">%s</a></div>',
				$addRecord->link,
				$this->icon,
				$this->name,
				$this->name,
				$this->name
				);
	}
	
}
