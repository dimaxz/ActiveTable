<?php

namespace ActiveTableEngine\Concrete\AutoResource;

use ActiveTableEngine\Concrete\EmptyPagination;
use ActiveTableEngine\Contracts\PaginationRenderInterface;
use ActiveTableEngine\Contracts\TableActionInterface;
use ActiveTableEngine\Contracts\TableRenderingInterface;
use ActiveTableEngine\Contracts\TableSortingInterface;


class DataTable implements TableRenderingInterface, TableSortingInterface {

	/**
	 * @var PaginationRenderInterface
	 */
	protected $pagination;
	protected $class;
	protected $header = [];
	protected $paginationCache;
	protected $name;
	protected $sortedFields =[];
	protected $baseUrl;

	function __construct($name, TableActionInterface $actionRequest) {

		$this->name = $name;
		$this->actionRequest = $actionRequest;
		$this->baseUrl = $actionRequest->getBaseUrl();
		$this->pagination = new EmptyPagination();
	}

	/**
	 * реализация навигации
	 *
	 * @param PaginationRenderInterface $pagination
	 *
	 * @return $this
	 */
	public function setPagination(PaginationRenderInterface $pagination) {

		$this->pagination = $pagination;

		return $this;
	}

	/**
	 * @param string $class
	 *
	 * @return $this
	 */
	public function setClass(string $class) {

		$this->class = $class;

		return $this;
	}

	public function renderBody(array $rows): string {

		$tc_blank = new \TagCollection();
		$tableTagBlank = &$tc_blank->addTag("TABLE");

		$render = new HtmlDataRender();

		$tableTagBlank->addAttribute("class", $this->class);

		$dt = $this->getRenderingData($rows);

		return $dt->render($render, $tc_blank);
	}

	/**
	 * получить отрендеренные таблицой данные
	 */
	public function getRenderingData(array $dataSource) {

		$dt = new DataGrid(md5(time()), "", $dataSource, new \Array_DataAdapter() );

		$url = $this->baseUrl;

		$sorted = $this->sortedFields;

		foreach ($this->header as $name => $caption) {

			$callback = new \dtCallBack([new \ActiveTableEngine\Concrete\AutoResource\FormaterHandler(), "run"], "<%currentSource%>");

			//$obj->exportable = (bool) $column->getExport();

			if(isset($sorted[$name]) && strtolower($sorted[$name]) == "asc"){
				$html = sprintf("<a href=\"%s\" title=\"Сортировать по убыванию\" >%s%s</a>", $url. "?" . http_build_query([
						"sort_by_" . $name => "desc"
					]), "<img src=\"/_sysimg/sort_desc.gif\" hspace=\"2\" border=\"0\" align=\"absmiddle\">",$caption);
			}
			elseif(isset($sorted[$name])&& strtolower($sorted[$name]) == "desc"){
				$html = sprintf("<a href=\"%s\" title=\"Сортировать по возрастанию\" ><span style=\"color:red\">%s%s</span></a>", $url . "?" . http_build_query([
						"sort_by_" . $name => "asc"
					]), "<img src=\"/_sysimg/sort_asc.gif\" hspace=\"2\" border=\"0\" align=\"absmiddle\">", $caption);
			}
			elseif(isset($sorted[$name])){
				$html = sprintf("<a href=\"%s\" title=\"Сортировать по убыванию\" >%s%s</a>", $url . "?" . http_build_query([
						"sort_by_" . $name => "desc"
					]), "<img src=\"/_sysimg/unsort.gif\" hspace=\"2\" border=\"0\" align=\"absmiddle\">", $caption);
			}
			else{
				$html = $caption;
			}

			$obj = $dt->addColumn(
				$name, $html, $callback
			);

		}

		$dt->readSource();

		return $dt;
	}

	public function renderHeader(array $rows): string {

		$this->header = $rows;

		return $this->getExportControls();
	}

	/**
	 * @param array $rows
	 *
	 * @return string
	 */
	public function renderTop(array $rows): string {

		$this->paginationCache = $this->pagination->render();
		$buttonAddHtml
			= (new ButtonAdd("Добавить новую запись", "/_sysimg/ar_add.png"))
			->render();

		return
			$this->buildFilter($rows) .
			$buttonAddHtml .
			$this->paginationCache;
	}

	/**
	 * @return string
	 */
	public function renderBottom(): string {

		$buttonAddHtml
			= (new ButtonAdd("Добавить новую запись", "/_sysimg/ar_add.png"))
			->render();

		return

			$this->paginationCache .
			$buttonAddHtml;
	}

	/**
	 * @param array $rows
	 */
	protected function buildFilter(array $rows){
		$formFilterHtml = $tpl ="";

		if(count($rows)){
			$formFilter = new \CustomForm($this->class . "_filter", $_SERVER['REQUEST_URI'], "GET");
			$reset = sprintf(
				'<input name="reset" value="Сбросить" class="submitButton" onclick="location.href = \'%s\'; return false;" style="visibility: visible;" type="submit">',
				$this->baseUrl
			);

			$html = "";

			foreach ($rows as $k => $field) {

				$formFilter->bindField($field[2]->getObject(),$field[0]);

				$html .=  '<td><%FormControl:' . $k . '@caption%></td><td><%FormControl:' . $k . '%></td>';
			}

			$tpl = '<table cellpadding="3" cellspacing="1" class="admin_edit_table">
    <tr><td><div><strong>' . tr("Фильтр") . '</strong></div></td>' . $html . '<td style="text-align: center"><%FormControl:apply%>' . $reset . '</td>
    </tr>
</table>';
			$formFilter
				->setStyle($tpl)
			;

			$formFilter->bindField(new \Submit("apply", tr("Применить", 'Common')));

			$formFilterHtml = $formFilter->render(new HtmlDataRender());
		}

		return $formFilterHtml;
	}

	public function setSortedFields(array $fields) {

		$this->sortedFields  = $fields;
		return $this;
	}

	/**
	 * Шаблон для экспорта
	 * @return string
	 */
	protected function getExportControls(): string {

		global $__BUFFER, $GLOBALS;

		$html = "";

		$render = new \PHP_DataRender;

		$tmp_get = '';
		if (count($_GET) > 0) {
			$tmp_get = encrypt(serialize($_GET), 'export');
		}

		// костыли для предобрабатываемых параметров
		if (!empty($_GET['mt_name'])) {
			$tmp_get .= '&mt_name=' . $_GET['mt_name'];
		}
		if (isset($_GET['archive'])) {
			$tmp_get .= '&archive=' . $_GET['archive'];
		}
		if (isset($_GET['dcm_id'])) {
			$tmp_get .= '&dcm_id=' . $_GET['dcm_id'];
		}
		if (isset($_GET['mode'])) {
			$tmp_get .= '&mode=' . $_GET['mode'];
		}
		// end костыли для предобрабатываемых параметров

		$exportUrl = $GLOBALS['_SYSTEM']->REQUESTED_PAGE . '?fn=exportTable&tname=' . $this->name . (!empty($tmp_get) ? '&par=' . $tmp_get : '');

		$valuesArray = array(
			'xls' => '<a href="' . $exportUrl . '&renderType=XLS" target="_blank" onclick="return setExportLink_' . $this->exportName . '(this);"><img src="/_sysimg/filetypes/csv16.png" border="0" alt="' . tr('экспорт в',
					'Forms') . ' xls" title="' . tr('экспорт в', 'Forms') . ' xls"></a>',
			'csv' => '<a href="' . $exportUrl . '&renderType=CSV" target="_blank" onclick="return setExportLink_' . $this->exportName . '(this);"><img src="/_sysimg/filetypes/csv16.png" border="0" alt="' . tr('экспорт в',
					'Forms') . ' csv" title="' . tr('экспорт в', 'Forms') . ' csv"></a>',
			'xml' => '<a href="' . $exportUrl . '&renderType=XML" target="_blank" onclick="return setExportLink_' . $this->exportName . '(this);"><img src="/_sysimg/filetypes/xml16.png" border="0" alt="' . tr('экспорт в',
					'Forms') . ' xml" title="' . tr('экспорт в', 'Forms') . ' xml"></a>',
		);

		if (class_exists('PHPExcel')) {
			$valuesArray = [
					'xlsx' => '<a href="' . $exportUrl . '&renderType=XLSX" target="_blank" onclick="return setExportLink_' . $this->exportName . '(this);"><img src="/_sysimg/filetypes/csv16.png" border="0" alt="' . tr('экспорт в',
							'Forms') . ' xlsx" title="' . tr('экспорт в', 'Forms') . ' xlsx"></a>'
				] + $valuesArray;
		}

		$rowsLimitForExportLabel = new \Label('rowsLimitForExportLabel_' . $this->name,
			tr('кол-во строк для экспорта:', 'Forms'));

		$html .= '<div class="ar_quickButton">' . $rowsLimitForExportLabel->render($render) . '</div>';

		$rowsLimitForExport = new \DigitalTextBox('rowsLimitForExport_' . $this->name, '1000', '50px');
		$rowsLimitForExport->addSetting('id', 'rowsLimitForExport_' . $this->name);
		$rowsLimitForExport->bindEvent('onkeydown', 'return((event.keyCode==13)?false:true)');

		$html .= '<div class="ar_quickButton">' . $rowsLimitForExport->render($render) . '</div>';

		$exportBox = new \NiceDropDownList("exportBox_" . $this->name, $valuesArray, null, '36px');

		$html .= '<div class="ar_quickButton">' . $exportBox->render($render) . '</div>';

		$__BUFFER->addScript('/_syslib/mootools.js');
		$__BUFFER->addScript('/_syslib/mootools-more.js');
		$__BUFFER->addScript('/_syslib/advanced.select.js');
		$__BUFFER->addContent(\PageBuffer::BUFFER_INDEX_HEADER_BOTTOM,
			"
<script>
function setExportLink_" . $this->name . "(linkObj) {

	var url = linkObj.href;
	url = url.replace(/&rowsLimit=[\d]*$/g,'');

	try {
		url = url + '&rowsLimit=' + $('rowsLimitForExport_" . $this->name . "').value;
	} catch(e) {}

	linkObj.href = url;

	return true;

}
</script>
					");

		return '<div class="ar_quickButtons">' . $html . '</div>';;
	}
}