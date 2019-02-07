<?

namespace ActiveTableEngine\Concrete\AutoResource;


/**
 * Description of HTML_Render
 *
 * @author d.lanec
 */
class HtmlDataRender extends \HTML_DataRender {

	function DataGrid_render(\DataGrid $dataGrid, $instance_TagCollection = null) {

		global $_MSG, $_interface;

		if (is_object($instance_TagCollection) && !is_a($instance_TagCollection, "TagCollection")) {

			trigger_error("Invalid TagCollection instance specified for DataGrid Render", E_USER_ERROR);

			return false;
		}

		$tc = is_null($instance_TagCollection) ? new TagCollection : clone $instance_TagCollection;

		$tableValue	 = "";
		$rowValue	 = "";
		$totalValue	 = "";

		if (!empty($dataGrid->columns)) {

			$multiCurrencyTableMode = $dataGrid->getSetting("multiCurrencyTableMode");

			foreach ($dataGrid->columns as $id => $column) {

				if (!$column->visible)
					continue;

				$column->caption = $_interface->leadRegister(tr($column->caption, 'Forms'));
				$th				 = clone $tc->needTag("TH");
				$th->addAttribute('class', 'col_' . $column->name);
				$th->value		 = $column->caption;

				if (is_array($dataGrid->sorted_columns) && array_key_exists($column->name, $dataGrid->sorted_columns)) {

					$sortName			 = str_replace(".", "_", $dataGrid->name);
					$sortedColumnName	 = $dataGrid->sorted_columns[$column->name];
					$descSortParamName	 = "sort_d_" . $sortName . "_by";
					$unsortParamName	 = "sort_" . $sortName . "_by";

					$a = new cLink($_SERVER['REQUEST_URI'], $column->caption);
					$a->addSetting("class", "sort_link")
							->removeQueryParam($descSortParamName)
							->removeQueryParam($unsortParamName);

					if (!empty($sortedColumnName))
						$a->addQueryParam($sortedColumnName, $column->name);

					$aImg = clone $a;

					$sortImage = "sort_asc.gif";
					if ($sortedColumnName === $descSortParamName) {
						$sortImage = 'sort_desc.gif';
					} else if ($sortedColumnName === $unsortParamName) {
						$sortImage = "unsort.gif";
					}
					$aImg->text = "<img src=\"/_sysimg/" . $sortImage . "\" border=0 align=absmiddle>";

					$th->value = "<table border=0><tr><td>" . $aImg->render($this) . "</td><td>" . $a->render($this) . "</td></tr></table>";
				}

				$rowValue .= $th->render($this);
			}

			$trH		 = clone $tc->needTag("TR");
			$trH->value	 = $rowValue;

			$tableValue	 .= $trH->render($this);
			$totalValues = [];

			$trRenderAll = "";

			$tableData = [];
			foreach ($dataGrid->rows as $rowId => $row) {

				$rowValue			 = "";
				$tr					 = clone $tc->needTag("TR");
				$tableData[$rowId]	 = [];

				foreach ($dataGrid->columns as $id => $column) {

					$tempData = $row->fields_assoc[$column->name];

					//$tempOutput = $tempData->{"get".$column->name}();

					$callable = is_string($column->data_container) ? extendUnserialize($column->data_container) : $column->data_container;

					//dump([$callable->value,$tempData,$column->name]);

					$tempOutput = $tempData; //call_user_func($callable->value,$tempData,$column->name);

					$tableData[$rowId][$column->name] = $tempOutput;


					if (!$column->visible)
						continue;



					$td = clone $tc->needTag("TD");

					if ($multiCurrencyTableMode && $multiCurrencyTableMode !== Currency_API::MC_TABLE_MODE_ALL_CURRENCIES && $tempData instanceof dtPriceParam) {
						$columnName = $column->name . Currency_API::MC_COST_FIELD_POSTFIX;
						if ($tableData[$rowId]['mc_cur_id'] !== $tableData[$rowId]['mc_cur_id_display'] && $row->fields_assoc[$columnName]) {
							$curInfo			 = Loader::getCurrencyApi()->getInfo($tableData[$rowId]['mc_cur_id']);
							$realCurrencyData	 = extendUnserialize($row->fields_assoc[$columnName]);
							if ($realCurrencyData->value !== "") {
								$realCurrencyData	 = new dtPriceParam($realCurrencyData->value, "", $curInfo['html_sign'], 1,
										$curInfo['position']);
								$tempOutput			 .= ' <sup class="real-cur-td__tooltip">' . $realCurrencyData->render($this) . '</sup>';
								$td->addAttribute("class", "real-cur-td");
							}
						}
					}

					if ($dataGrid->total_columns[$column->name]) {
						$roundValue = round(floatval($tempData->value * 100)) / 100;

						if ($multiCurrencyTableMode === Currency_API::MC_TABLE_MODE_ALL_CURRENCIES) {
							$totalValues[$column->name][$tableData[$rowId]['mc_cur_id']] += $roundValue;
						} else {
							$totalValues[$column->name] += $roundValue;
						}
					}

					$td->addAttribute('class', 'col_' . $column->name);
					$td->value	 = $tempOutput != "" ? $tempOutput : "&#160;";
					$rowValue	 .= $td->render($this);
				}

				if (isset($dataGrid->rowsStyles[$rowId]))
					$tr->addOption($dataGrid->rowsStyles[$rowId]);

				$tr->value	 = $rowValue;
				$trRender	 = str_replace("<%rowId%>", $rowId, $tr->render($this));

				$trRenderAll .= $trRender;
			}

			if (!empty($dataGrid->total_columns)) {


				$tr = clone $tc->needTag("TR");

				$columnsShift = 0;
				foreach ($dataGrid->columns as $id => $column) {

					if ($column->visible) {
						if ($dataGrid->total_columns[$column->name] === 'average') {
							$columnsShift = 0;
							break;
						}

						if (isset($totalValues[$column->name]))
							break;
						$columnsShift++;
					}
				}

				$rowValue = "";
				if ($columnsShift > 0) {
					$td			 = clone $tc->needTag("TH");
					$td->addAttribute("class", "total total--sum");
					$td->addAttribute("colspan", $columnsShift);
					$td->value	 = '<span class="total__item--sum">' . tr('Итого') . ':</span>';
					$rowValue	 = $td->render($this);
				}

				$columnIndex = 0;

				foreach ($dataGrid->columns as $id => $column) {

					if (!$column->visible)
						continue;

					$columnIndex++;
					if ($columnIndex <= $columnsShift)
						continue;

					$td					 = clone $tc->needTag("TH");
					$td->addAttribute("class", "total total__column--" . $column->name);
					$columnTotalValue	 = $totalValues[$column->name];

					if (isset($columnTotalValue)) {

						if (is_array($columnTotalValue)) {
							foreach ($columnTotalValue as $curId => $value) {
								$curInfo	 = Loader::getApi('currency')->getInfo($curId);
								$tempData	 = new dtPriceParam($this->getTotalCellValueByType($dataGrid, $value,
												$dataGrid->total_columns[$column->name], false), "", $curInfo['cur_iso'], 1);
								$td->value	 .= $tempData->render($this);
							}
							$td->value = $this->dataGridTotalCellWrap($td->value, $dataGrid->total_columns[$column->name], $columnsShift);
						} else {

							$tempData = extendUnserialize($row->fields_assoc[$column->name]);

							if (!empty($tempData->name)) {
								$tempData->name = 'total_' . $tempData->name;
							}

							$tempData->id	 = $tempData->name . $tempData->instanceCount . $rowId;
							$tempData->value = $this->getTotalCellValueByType($dataGrid, $columnTotalValue,
									$dataGrid->total_columns[$column->name]);

							$td->value	 = $tempData->render($this, $tc);
							$td->value	 = strpos($td->value, 'INPUT') !== false ? $tempData->value : $td->value;

							$td->value = $this->dataGridTotalCellWrap($td->value, $dataGrid->total_columns[$column->name], $columnsShift);
						}
					}

					$rowValue .= $td->render($this);
				}

				$tr->value = $rowValue;

				$totalValue .= $tr->render($this);
			}
		}

		if ($dataGrid->getSetting("BOTTOM_HEADER"))
			$tableValue .= $totalValue;

		$tableValue	 .= $trRenderAll;
		$tableValue	 .= $totalValue;

		if ($dataGrid->getSetting("BOTTOM_HEADER"))
			$tableValue .= $trH->render($this);

		$controls	 = (array) $dataGrid->controls;
		$caption	 = clone $tc->needTag("CAPTION");

		$captionTopBuffer	 = "";
		$captionBottomBuffer = "";

		foreach ($controls as $control) {

			$controlTagCollection = $tc;
			if (!empty($dataGrid->controlsRenderParams[$control->name])) {

				$controlTagCollection = $dataGrid->controlsRenderParams[$control->name];
			}

			$captionValueBuffer = $control->render($this, $controlTagCollection);

			if ($caption->attributes['align']->valueRef == "bottom") {

				$captionBottomBuffer .= "<div>" . $captionValueBuffer . "</div>";
			} else {

				$captionTopBuffer .= "<div align=\"" . $caption->attributes['align']->valueRef . "\">" . $captionValueBuffer . "</div>";
			}

			$caption->render($this);
		}

		$topCaptionDiv = "";
		if ($captionTopBuffer != "") {
			$topCaptionDiv = '<div style="text-align:center">' . $captionTopBuffer . '</div>';
		}

		$bottomCaptionDiv = "";
		if ($captionBottomBuffer != "") {
			$bottomCaptionDiv = '<div style="text-align:center">' . $captionBottomBuffer . '</div>';
		}

		$quickButtons		 = &$dataGrid->quickButtons;
		$quickButtonsBuffer	 = "";
		$quickButtonsDiv	 = "";

		if (!empty($quickButtons)) {

			foreach ($quickButtons as $quickButton) {

				if (empty($quickButton))
					continue;

				if (!empty($dataGrid->quickButtonsRenderParams[$quickButton->name])) {

					$quickButtonValueBuffer = $quickButton->render($this, $dataGrid->quickButtonsRenderParams[$quickButton->name]);
				} else {

					$quickButtonValueBuffer = $quickButton->render($this, $tc);
				}

				$quickButtonsBuffer .= '<div class="ar_quickButton">' . $quickButtonValueBuffer . '</div>';
			}

			$quickButtonsDiv = '<div class="ar_quickButtons">' . $quickButtonsBuffer . '</div>';
		}

		$captionValue = "";
		if (!empty($dataGrid->caption)) {

			$caption->addAttribute("class", "DataGridCaption");
			$caption->value	 = $dataGrid->caption;
			$captionValue	 = $caption->render($this);
		}

		$table = clone $tc->needTag("TABLE");
		$table->addAttribute("data-id", $dataGrid->name);

		foreach ($dataGrid->getAttributes() as $attrName => $attributeValue) {
			$table->addAttribute($attrName, $attributeValue);
		}

		if ($dataGrid->rowsCount > 0) {

			$table->value = $captionValue . $tableValue;
		} else {

			$table->addAttribute("width", "");
			$table->value	 = $captionValue;
			$table->value	 .= "<tr><td>" . (!empty($dataGrid->settings["EMPTY_MESSAGE"]) ? $dataGrid->settings["EMPTY_MESSAGE"] : $_MSG['lib.html-render.php']['2']) . "</td></tr>";
		}

		$otherSettingsRender = "";
		if ($dataGrid->hasSetting('multiCurrencyFilter')) {
			$otherSettingsRender .= $dataGrid->getSetting('multiCurrencyFilter');
		}

		return $topCaptionDiv . $quickButtonsDiv . $otherSettingsRender . $table->render($this) . $bottomCaptionDiv;
	}

}
