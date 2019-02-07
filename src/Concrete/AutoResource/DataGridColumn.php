<?

namespace ActiveTableEngine\Concrete\AutoResource;

/**
 * Description of DataGridColumn
 *
 * @author d.lanec
 */
class DataGridColumn extends \DataGrid_Column {
	
	function __construct($name, $caption, $DataTypeInstance, $visible = true, $clm_info = null, $exportable = false, $export_template = '', $translatable = false) {

		$this->name = $name;
		$this->caption = $caption;
		$this->data_container = $DataTypeInstance;
		$this->visible = $visible;
		$this->clm_info = $clm_info;
		$this->exportable = $exportable;
		$this->translatable = $translatable;
		$this->export_template = $export_template;

	}	
	
}
