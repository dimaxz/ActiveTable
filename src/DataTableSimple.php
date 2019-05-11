<?php

namespace ActiveTableEngine;


use ActiveTableEngine\Contracts\ActionInterface;
use ActiveTableEngine\Contracts\ColumnTableInterface;
use ActiveTableEngine\Contracts\FormControlRenderInterface;
use Repo\CrudRepositoryInterface;
use ActiveTableEngine\Contracts\OutputInterface;
use ActiveTableEngine\Contracts\TableRenderingInterface;
use ActiveTableEngine\Contracts\FormRenderingInterface;

class DataTableSimple {

	const BUTT = "";
	
	protected $fieldsWidth;

	protected $repo;
	protected $table;
	protected $form;

	protected $reqFields = [];
	protected $fields = [];
	protected $fieldsCaptions = [];

	protected $columns = [];
	protected $columnCaptions = [];

	protected $filterFields = [];
	protected $filterFieldsCaptions = [];

	protected $actionFields = [];
	protected $actionFieldsCaptions = [];

	protected $actionSelectFields = [];
	protected $actionSelectCaptions = [];

	protected $searchCriteria;

	private $rows = [];

	protected $action;
	protected $output;
	protected $name;

	protected $defaultAction = "";

	/**
	 * DataTableSimple constructor.
	 *
	 * @param $repo
	 * @param $name
	 */
	function __construct(\Repo\CrudRepositoryInterface $repo,$name) {
		$this->repo = $repo;
		$this->actionRequest = new Concrete\TableAction();
		$this->table = new Concrete\EasyTable($name,$this->actionRequest);
		$this->form = new Concrete\EasyForm($name,$this->actionRequest);
		$this->searchCriteria = new Concrete\Navigation();
		$this->output = new Concrete\ContentOutput();
		$this->action = new Concrete\ActionHandler();
		$this->name = $name;
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public function setDefaultAction(string $name){
		$this->defaultAction = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName():string{

		return $this->name;
	}

	/**
	 * @param mixed $fieldsWidth
	 */
	public function setFieldsWidth($fieldsWidth) {

		$this->fieldsWidth = $fieldsWidth;
		return $this;
	}



	/**
	 * @param OutputInterface $content
	 *
	 * @return $this
	 */
	public function setOutputContent(OutputInterface $content){
		$this->output = $content;
		return $this;
	}

	/**
	 * @param Concrete\TableAction $action
	 *
	 * @return $this
	 */
	public function setTableAction(Concrete\TableAction $action){
		$this->actionRequest = $action;
		return $this;
	}



	/**
	 * Установка драйвера таблицы
	 * @param TableRenderingInterface $table
	 *
	 * @return $this
	 */
	public function setTableDriver(TableRenderingInterface $table){
		$this->table = $table;
		return $this;
	}

	/**
	 * Установка драйвера формы
	 * @param FormRenderingInterface $form
	 *
	 * @return $this
	 */
	public function setFormDriver(FormRenderingInterface $form){
		$this->form = $form;
		return $this;
	}

	/**
	 * @return FormRenderingInterface
	 */
	public function getFormDriver():FormRenderingInterface{
		return $this->form;
	}

	/**
	 * @return TableRenderingInterface
	 */
	public function getTableDriver():TableRenderingInterface{
		return $this->table;
	}

	/**
	 * Установка объекта для фильтрации для репозитория
	 * @param $criteria
	 *
	 * @return $this
	 */
	public function setSearchCriteria($criteria){
		$this->searchCriteria = $criteria;
		return $this;
	}

	/**
	 * Рендерим таблицу в зависимости от действия
	 *
	 * @return string
	 * @throws Exceptions\ActionError
	 */
	public function render(): string {

//		$this->buildControls();
//
//		$hiddenFilter = true;



		switch (true) {

			case $this->definitionAction() == ActionName::ACTION_DELETE:

				try {
					$this->action->call(EventName::BEFORE_DELETE);
					$this->action->call(EventName::ON_DELETE);
					$this->action->call(EventName::AFTER_DELETE);
				} catch(Exceptions\ActionError $ex) {

				}


				break;

			case $this->definitionAction() == ActionName::ACTION_CREATE:

				try {
					$this->action->call(EventName::BEFORE_CREATE);
					$this->action->call(EventName::ON_CREATE);
					$this->action->call(EventName::AFTER_CREATE);
				} catch(Exceptions\ActionError $ex) {

					$this->action->call(EventName::BEFORE_FORM_RENDER);
					$this->output->addContent($this->renderForm());
				}


				break;

			case $this->definitionAction() == ActionName::ACTION_UPDATE:

				try {
					$this->action->call(EventName::BEFORE_UPDATE);
					$this->action->call(EventName::ON_UPDATE);
					$this->action->call(EventName::AFTER_UPDATE);
				} catch(Exceptions\ActionError $ex) {
					$this->action->call(EventName::BEFORE_FORM_RENDER);
					$this->output->addContent($this->renderForm());
				}


				break;

			case $this->definitionAction() == ActionName::ACTION_READ:

				try {

					$this->action->call(EventName::BEFORE_READ);
					$this->action->call(EventName::ON_READ);
					$this->action->call(EventName::AFTER_READ);
					$this->action->call(EventName::BEFORE_FORM_RENDER);

					$html = $this->renderForm();

					$this->output->addContent($html);
				} catch(Exceptions\ActionError $ex) {

				}


				break;

			case $this->definitionAction() == ActionName::ACTION_VIEW_FORM:

				$this->action->call(EventName::BEFORE_FORM_RENDER);
				$this->output->addContent($this->renderForm());

				break;

			case !empty($this->defaultAction):

				$this->action->call($this->defaultAction);

				break;

			default:

				$this->action->call(EventName::BEFORE_TABLE);
				$this->action->call(EventName::BEFORE_TABLE_RENDER);
				$this->output->addContent($this->renderTable());
				$this->action->call(EventName::AFTER_TABLE);

				//$hiddenFilter = false;

				break;
		}

//		if ($hiddenFilter === false) {
//			return $this->getTableWithFilterActionHtml($this->output->getContent());
//		}


		return $this->output->getContent();
	}


	/**
	 * Определение действия
	 */
	public function definitionAction(): string {

		switch (TRUE) {
			case $this->actionRequest->isDeleteRecord():

				return ActionName::ACTION_DELETE;

				break;


			case $this->actionRequest->isUpdateRecord():

				return ActionName::ACTION_UPDATE;

				break;

			case $this->actionRequest->isCreateRecord():

				return ActionName::ACTION_CREATE;

				break;

			case $this->actionRequest->isViewRecord():

				return ActionName::ACTION_READ;

				break;


			case $this->actionRequest->isViewForm():

				return ActionName::ACTION_VIEW_FORM;

				break;


			default:
				return $this->defaultAction;
				break;
		}

	}



	/**
	 * Рендеринг частей формы
	 * @return string
	 */
	protected function renderForm():string{

		$fields = $this->getFields();

		return
			$this->form->renderHeader() .
			$this->form->renderBody($fields) .
			$this->form->renderBottom()
			;
	}

	/**
	 * Логика рендеринга таблицы
	 * @return string
	 */
	protected function renderTable():string{
		$rows 	= $this->getRows();
		$header = $this->getHeader();

		$this->prepareSortingHeader($header);

		$filters = $this->getFilters();

		return
			$this->table->renderTop($filters) .
			$this->table->renderHeader($header) .
			$this->table->renderBody($rows) .
			$this->table->renderBottom()
			;
	}


	/**
	 * Получение массива с полями
	 * @todo вместо сложного массива использовать класс-структуру
	 * @return array
	 */
	protected function getFields():array{

		$fields = [];

		$data = $this->output->getData();

		foreach($this->fields as $name => $field){

			if(isset($this->filterFieldsCaptions[$name]) && !empty($this->filterFieldsCaptions[$name])){
				$caption = $this->filterFieldsCaptions[$name];
			}
			elseif(isset($this->columnCaptions[$name]) && !empty($this->columnCaptions[$name])){
				$caption = $this->columnCaptions[$name];
			}
			else{
				$caption = $name;
			}

			if(isset($data[$name])){

				$field->setValue($data[$name]);
			}

			if(!empty($this->fieldsWidth)){
				$field->setWidth($this->fieldsWidth);
			}

			$fields[$name] = [
				$caption,//caption
				$this->renderControl($field),//new control
				$field,//orig object
				$this->reqFields[$name]//required
			];

		}


		return $fields;
	}

	/**
	 * @param int $width
	 *
	 * @return $this
	 */
	public function setWidthFields(int $width){
		$this->fieldsWidth = $width;
		return $this;
	}


	/**
	 * Получение массива фильтров
	 * @todo вместо сложного массива класс-структура
	 * @return array
	 */
	public function getFilters():array{

		$filters = [];

		foreach ($this->filterFields as $name => $filterField) {

			if(isset($this->filterFieldsCaptions[$name]) && !empty($this->filterFieldsCaptions[$name])){
				$caption = $this->filterFieldsCaptions[$name];
			}
			elseif(isset($this->columnCaptions[$name]) && !empty($this->columnCaptions[$name])){
				$caption = $this->columnCaptions[$name];
			}
			else{
				$caption = $name;
			}


			$filters[$name] = [
				$caption,
				$this->renderControl($filterField),
				$filterField
			];
		}

		return $filters;
	}

	/**
	 * @param $control
	 * @return mixed
	 */
	protected function renderControl(FormControlRenderInterface $control): string{
		return $control->render();
	}


	/**
	 * Подготовака массива с указанием полей сортировки
	 * @todo избавиться от $_GET
	 * @param array $header
	 */
	protected function prepareSortingHeader(array $header){
		$sorted = [];

		$getData = array_change_key_case($_GET);
		
		foreach ($this->columns as $column){

			$name = $column->getName();

			if($column->isSorted()===true){
				$name = strtolower(sprintf("sort_by_%s", $column->getName()));

				$sorted[$column->getName()] = "";

				if(isset($getData[$name]) && $getData[$name]=="desc"){
					$sorted[$column->getName()] = "DESC";
				}
				elseif(isset($getData[$name]) && $getData[$name]=="asc"){
					$sorted[$column->getName()] = "ASC";
				}

			}
		}

		$this->table->setSortedFields($sorted);
	}

	/**
	 * Получение массива шапки
	 *
	 * get header row
	 * @return array
	 */
	protected function getHeader():array{

		$header = [];

		if(count($this->columns)){
			foreach ($this->columns as $column){
				$header[$column->getName()] = $column->getCaption();
			}
		}
		elseif(count($this->rows)){
			$header = array_shift($this->rows);
		}

		return $header;
	}

	/**
	 * Логика извлечения данных из репозитория
	 * get main rows
	 * @return array
	 */
	protected function getRows():array{
		$collection = $this->repo->findByCriteria($this->searchCriteria);

		$rows = [];

		foreach ($collection as $model){
			foreach ($this->columns as $column){
				$name = $column->getName();
				if(is_callable($column->getFormat())){
					$row[$name] = call_user_func_array($column->getFormat(),[$model]);
				}
				else{
					$row[$name] = $model->{"get" . $name}();
				}

			}
            $rows[]=$row;
		}

		$this->rows = $rows;
		return $this->rows;
	}

	/**
	 * Добавление поля
	 *
	 * @param FormControlRenderInterface $control
	 * @param bool                      $req
	 * @param bool                      $caption
	 *
	 * @return $this
	 */
	public function addField(FormControlRenderInterface $control, $req = false, $caption = false) {
		$name = strtolower($control->getName());
		$this->fields[$name] = $control;
		$this->reqFields[$name] = $req;
		$this->filterFieldsCaptions[$name] = $caption;
		return $this;
	}

	/**
	 * Добавление фильтра
	 * @param FormControlRenderInterface $control
	 * @param bool                      $caption
	 *
	 * @return $this
	 */
	public function addFilter(FormControlRenderInterface $control, $caption = false) {
		$name = strtolower($control->getName());
		$this->filterFields[$name] = $control;
		$this->filterFieldsCaptions[$name] = $caption;
		return $this;
	}

	/**
	 * Добавления действия для всех записей
	 *
	 * @param FormControlRenderInterface $control
	 * @param null                      $caption
	 *
	 * @return $this
	 */
	public function addAction(FormControlRenderInterface $control, $caption = null) {
		$name = strtolower($control->getName());
		$this->actionFields[$name] = $control;
		$this->actionFieldsCaptions[$name] = $caption;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getActions(): array {

		$actions = [];

		foreach ($this->actionFields as $name => $actionField) {

			if(isset($this->actionFieldsCaptions[$name]) && !empty($this->actionFieldsCaptions[$name])){
				$caption = $this->actionFieldsCaptions[$name];
			}
			elseif(isset($this->columnCaptions[$name]) && !empty($this->columnCaptions[$name])){
				$caption = $this->columnCaptions[$name];
			}
			else{
				$caption = $name;
			}


			$actions[$name] = [
				$caption,
				$this->renderControl($actionField),
				$actionField
			];
		}

		return $actions;
	}

	/**
	 * @return array
	 */
	public function getSelectActions(): array {
		return $this->actionSelectFields;
	}

	/**
	 * Добавление действия для выбранных позиций
	 *
	 * @param FormControlRenderInterface $control
	 * @param null                      $caption
	 *
	 * @return $this
	 */
	public function addActionSelect(FormControlRenderInterface $control, $caption = null) {
		$name = strtolower($control->getName());
		$this->actionSelectFields[$name] = $control;
		$this->actionSelectCaptions[$name] = $caption;
		return $this;
	}

	/**
	 * Добавление колонки
	 *
	 * @param ColumnTableInterface $column
	 *
	 * @return $this
	 */
	public function addColumn(ColumnTableInterface $column){
		$name = strtolower($column->getName());
		$this->columns[$name]  = $column;
		$this->columnCaptions[$name] = $column->getCaption();
		return $this;
	}

	/**
	 * Добавление команды на событие
	 * @param                 $event
	 * @param ActionInterface $actionCommand
	 *
	 * @return DataTableSimple
	 */
	public function addActionCommand($event, ActionInterface $actionCommand): DataTableSimple{
		$this->action->add($event,$actionCommand);
		return $this;
	}
}