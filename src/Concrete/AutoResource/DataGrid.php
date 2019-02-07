<?

namespace ActiveTableEngine\Concrete\AutoResource;



/**
 * Description of DataGrid
 *
 * @author d.lanec
 */
class DataGrid extends \DataGrid {

	
	
	function &addColumn($name, $caption, $DataTypeInstance, $visible = true, $clm_info = '', $pos = false, $exportable = true, $export_template = '', $translatable = false) {

		if (is_object($DataTypeInstance) && is_a($DataTypeInstance, "DataType")) {

			if (is_a($DataTypeInstance, "FormControl") && !preg_match("/\[[^\]]+\]/", $DataTypeInstance->name) && stristr(get_class($DataTypeInstance), "protected") === false) {

				if (!is_a($DataTypeInstance, "nicedropdownlist"))
					$DataTypeInstance->name .= "[<%rowsCount%>]";
				else
					$DataTypeInstance->name .= "_<%rowsCount%>";

			}

		} else {
			trigger_error("Invalid DataType Instance specified for new DataGrid column $name \"$caption\"");

			return false;
		}

		
		$instance_DataGrid_Column = new DataGridColumn($name, $caption, $DataTypeInstance, $visible, $clm_info, $exportable, $export_template, $translatable);

		$this->columns_assoc[$instance_DataGrid_Column->name] = & $instance_DataGrid_Column;

		if ($pos) {
			$pos--;
			$this->columns = array_merge(array_slice($this->columns, 0, $pos), array(&$this->columns_assoc[$instance_DataGrid_Column->name]), array_slice($this->columns, $pos));
		} else {
			$this->columns[] = & $this->columns_assoc[$instance_DataGrid_Column->name];
		}

		return $instance_DataGrid_Column;

	}	
	
	
	function readSource($afterTableResults = []) {

		global $CONST, $CACHEABLE_timer;

		if (!$this->checkSource()) {
			return false;
		}

		$CACHEABLE_timer->addPoint('ActiveTableEngine\Concrete\AutoResource\DataGrid readSource', $this->name);

		$this->adapter->move_first($this->datasource);

		$pk = $this->primaryKey[0];

		$useAfterResults = !empty($afterTableResults);

		while ($output = $this->adapter->fetch_row_assoc($this->datasource)) {

			if ($useAfterResults) {
				$output = array_merge($output, $afterTableResults[$output[$pk]]);
			}

			$this->rowsCount++;

			$this->currentSource = & $output;
			$row = & $this->addRow();

			if (is_array($this->columns)) {

				foreach ($this->columns as $id => $column) {

					$row->addField(
						$column->name,
						call_user_func($column->data_container->callBackFunction, $this->currentSource,$column->name )
					);

					unset($fillValue);

				}

			}

		}

		$CACHEABLE_timer->endPoint();

		return true;

	}

	
}
