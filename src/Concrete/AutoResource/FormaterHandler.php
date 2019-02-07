<?

namespace ActiveTableEngine\Concrete\AutoResource;

/**
 * Description of FormaterHandler
 *
 * @author d.lanec
 */
class FormaterHandler{
	
	function run($row,$field){
		return $row[$field];
	}
	
}
