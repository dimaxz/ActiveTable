<?php

namespace ActiveTable;

use ActiveTable\Contracts\CommandFactoryInterface;
use ActiveTable\Contracts\CommandInterface;
use ActiveTable\Contracts\FormControlRenderInterface;
use ActiveTable\EmptyControls\Content;
use Core\Form\Control\FormControl;
use Psr\Http\Message\ServerRequestInterface;
use Repo\CrudRepositoryBuilderInterface;
use Repo\CrudRepositoryInterface;
use Repo\EntityInterface;
use Repo\PaginationInterface;

class DataTableEngine
{

    private string|null $formTemplate = null;

    /**
     * @var EntityInterface
     */
    protected $tableRowEntity;

    /**
     * @var array
     */
    protected $defaultSortColumn = [];

    /**
     * @var CrudRepositoryInterface
     */
    protected $repo;

    /**
     * @var string
     */
    protected $name;

    /**
     * класс выдающий действие со справочником
     * @var CommandFactoryInterface
     */
    protected $commandFactory;

    /**
     * колонки таблицы
     * @var array
     */
    protected $columns = [];

    /**
     * поля форм
     * @var array
     */
    protected $fields = [];

    /**
     * табличные фильтры
     * @var array
     */
    protected $filters = [];

    /**
     * контролы действий для всех записей таблицы
     * @var array
     */
    protected $actions = [];

    /**
     * Кнопки в правой части таблицы
     * @var array
     */
    protected $buttons = [];

    /**
     * Кнопки в левой части таблицы
     * @var array
     */
    protected $actionButtons = [];

    /**
     * контролы для выборочных записей таблицы, активирют чекбоксы
     * @var array
     */
    protected $rowActions = [];

    /**
     * table class
     * @var string
     */
    protected $class;

    /**
     * @var string|null
     */
    protected $id;

    /**
     * form class
     * @var string
     */
    protected $formClass;

    /**
     * @var
     */
    protected $fieldsWidth;

    /**
     * @var Content
     */
    protected $output;

    protected $request;

    /**
     * @var array
     */
    protected $captions = [];

    /**
     * касмтомный перечень контролов по умолчанию
     * @var array
     */
    protected $controlAccess = [];

    const CONTROL_ACCESS_EDIT = "edit";
    const CONTROL_ACCESS_DELETE = "delete";
    const CONTROL_ACCESS_ADD = "add";
    const CONTROL_ACCESS_EXPORT = 'export';
    const CONTROL_PAGINATION = "pagination_view";
    const CONTROL_ROWS_ACTION = "select_rows_action";
    const CONTROL_ROWS_SELECT = "select_rows";
    const CONTROL_FILTER_BUTTON = 'filter_button';
    const CONTROL_FORM_SAVE_BUTTON = 'form_save_button';
    const CONTROL_FORM_CANCEL_BUTTON = 'form_cancel_button';


    /**
     * критерия выборки из репо нужна для навигации фильтрации и тд. по сути с ним только работает репозиторий
     * @var PaginationInterface
     */
    protected $criteria;

    /**
     * Кол-во колонок для авторазбивки фильтров
     * @var int|null
     */
    protected $filterColumns;

    protected $topControls = true;
    protected $bottomControls = true;
    protected string|null $addButtonTitle = null;

    protected array $tabs = [];
    protected array $fieldGroups = [];
    protected string|null $makeTab = null;
    protected string|null $makeFieldGroup = null;

    protected string $formEditText = 'Редактирование записи №';
    protected string $formAddText = 'Новая запись';

    function __construct(CrudRepositoryInterface $repo, string $name, CommandFactoryInterface $commandFactory,
                         ServerRequestInterface $request, PaginationInterface $criteria)
    {
        $this->repo = $repo;
        $this->name = $name;
        $this->commandFactory = $commandFactory;
        $this->output = new Content();
        $this->request = $request;
        $this->criteria = $criteria;
    }

    /**
     * @return string
     */
    public function getFormEditText(): string
    {
        return $this->formEditText;
    }

    /**
     * @param string $formEditText
     * @return DataTableEngine
     */
    public function setFormEditText(string $formEditText): DataTableEngine
    {
        $this->formEditText = $formEditText;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormAddText(): string
    {
        return $this->formAddText;
    }

    /**
     * @param string $formAddText
     * @return DataTableEngine
     */
    public function setFormAddText(string $formAddText): DataTableEngine
    {
        $this->formAddText = $formAddText;
        return $this;
    }

    /**
     * @return array
     */
    public function getFieldGroups(): array
    {
        return $this->fieldGroups;
    }


    public function startFieldGroup(string $group): self
    {
        $this->makeFieldGroup = $group;
        return $this;
    }

    public function endFieldGroup(): self
    {
        $this->makeFieldGroup = null;
        return $this;
    }


    /**
     * @return array
     */
    public function getTabs(): array
    {
        return $this->tabs;
    }


    public function startTab(string $tab): self
    {
        $this->makeTab = $tab;
        return $this;
    }

    public function endTab(): self
    {
        $this->makeTab = null;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddButtonTitle()
    {
        return $this->addButtonTitle;
    }

    /**
     * @param mixed $addButtonTitle
     * @return DataTableEngine
     */
    public function setAddButtonTitle(string $addButtonTitle)
    {
        $this->addButtonTitle = $addButtonTitle;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function setTableRowEntity(EntityInterface $tableRowEntity): DataTableEngine
    {
        $this->tableRowEntity = $tableRowEntity;
        return $this;
    }

    public function getTableRowEntity(): EntityInterface
    {

        return $this->tableRowEntity;
    }

    public function loadFormEntity(int $id = null): ?EntityInterface
    {
        if(!$id){
            if(!$id = $this->getCriteria()->getFilterById()){
                return null;
            }
        }

        if(!$result = $this->getRepo()->findByCriteria(
            $this->getCriteria()->setFilterById($id)->setPage(0)
        )->current()){
            return null;
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function getDefaultSortColumn(): array
    {
        return $this->defaultSortColumn;
    }

    /**
     * @param mixed $defaultSortColumn
     * @return DataTableEngine
     */
    public function setDefaultSortColumn(string $defaultSortColumn, string $order)
    {
        $this->defaultSortColumn[$defaultSortColumn] = $order;
        return $this;
    }

    /**
     * @param FormControlRenderInterface $field
     * @return $this
     */
    public function addButton(FormControlRenderInterface $field)
    {
        $this->buttons [] = $field;
        return $this;
    }

    /**
     * @param FormControlRenderInterface $field
     * @return $this
     */
    public function addActionButton(FormControlRenderInterface $field)
    {
        $this->actionButtons [] = $field;
        return $this;
    }

    /**
     * @return array
     */
    public function getButtons(): array
    {
        return $this->buttons;
    }

    /**
     * @return array
     */
    public function getActionButtons(): array
    {
        return $this->actionButtons;
    }

    /**
     * @param bool $topControls
     * @return DataTableEngine
     */
    public function setTopControls(bool $topControls): DataTableEngine
    {
        $this->topControls = $topControls;
        return $this;
    }

    /**
     * @param bool $bottomControls
     * @return DataTableEngine
     */
    public function setBottomControls(bool $bottomControls): DataTableEngine
    {
        $this->bottomControls = $bottomControls;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTopControls(): bool
    {
        return $this->topControls;
    }

    /**
     * @return bool
     */
    public function isBottomControls(): bool
    {
        return $this->bottomControls;
    }


    /**
     * @return CommandFactoryInterface
     */
    public function getCommandFactory(): CommandFactoryInterface
    {
        return $this->commandFactory;
    }

    /**
     * @param int|null $filterColumns
     * @return DataTableEngine
     */
    public function setFilterColumns(?int $filterColumns): DataTableEngine
    {
        $this->filterColumns = $filterColumns;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFilterColumns(): ?int
    {
        return $this->filterColumns;
    }

    /**
     * формирование комманды
     */
    private function prepareCommand(): void
    {
        $this->commandFactory->build($this)->process();
    }

    /**
     * @return string
     */
    public function render(): string
    {

        $this->prepareCommand();
        return $this->output->getContent();
    }

    /**
     * @param string $name
     * @return DataTableEngine
     */
    public function addControlAccess(string $name): DataTableEngine
    {
        if (!in_array($name, $this->controlAccess)) {
            $this->controlAccess [] = $name;
        }

        return $this;
    }

    /**
     * @param array $names
     * @return DataTableEngine
     */
    public function setControlAccess(array $names): DataTableEngine
    {
        $this->controlAccess = $names;
        return $this;
    }

    /**
     * @param string $name
     * @return DataTableEngine
     */
    public function removeControlAccess(string $name): DataTableEngine
    {
        $key = array_search($name, $this->controlAccess);

        if ($key !== false) {
            unset($this->controlAccess[$key]);
        }

        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasControlAccess(string $name): bool
    {
        return in_array($name, $this->controlAccess);
    }

    /**
     * Добавление колонки
     *
     * @param ColumnTable $column
     * @return $this
     */
    public function addColumn(ColumnTable $column): self
    {
        $this->columns [] = $column;
        $this->captions[$column->getName()] = $column->getCaption();
        return $this;
    }

    /**
     * Добавление колонки
     *
     * @param ColumnTable $column
     * @return $this
     */
    public function addFirstColumn(ColumnTable $column): self
    {
        $this->columns = array_merge([$column],$this->columns);
        return $this;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return CrudRepositoryBuilderInterface
     */
    public function getRepo(): CrudRepositoryBuilderInterface
    {
        return $this->repo;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return Content
     * @see self::clearContent
     * @see self::addContent
     * @deprecated
     */
    public function getOutput(): Content
    {
        return $this->output;
    }


    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }


    /**
     * @param ServerRequestInterface $request
     * @return DataTableEngine
     */
    public function setRequest(ServerRequestInterface $request): DataTableEngine
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return PaginationInterface
     */
    public function getCriteria(): PaginationInterface
    {
        return $this->criteria;
    }

    /**
     * @param PaginationInterface $criteria
     * @return DataTableEngine
     */
    public function setCriteria(PaginationInterface $criteria): DataTableEngine
    {
        $this->criteria = $criteria;
        return $this;
    }

    public function setFormTemplate(string $template): DataTableEngine
    {
        $this->formTemplate = $template;
        return $this;
    }

    /**
     * @param FormControlRenderInterface $field
     */
    public function addField(
        FormControlRenderInterface $field, bool $require = false,
        string $caption = null, string $help = null, array $validations = []): DataTableEngine
    {

        if($this->makeTab !== null){
            $this->tabs[$this->makeTab][]=$field->getName();
        }

        if($this->makeFieldGroup !== null){
            $this->fieldGroups[$this->makeFieldGroup][]=$field->getName();
        }

        $control = (new FormField($field))
            ->setRequire($require)
            ->setCaption($this->makeCaption($field, $caption))
            ->setHelpCaption($help)
            ->setCustomValidations($validations);

        $fields = $this->fields;

        //если уже есть контрол, переопределим его
        foreach ($fields as $k => $fieldExist) {
            if ($field->getName() === $fieldExist->getControl()->getName()) {
                $this->fields[$k] = $control;
                return $this;
            }
        }

        $this->fields[] = $control;

        return $this;
    }

    /**
     * @param string $nameControl
     * @return DataTableEngine
     */
    public function removeFieldByName(string $nameControl): self
    {
        $fields = $this->fields;
        foreach ($fields as $k => $field) {
            if ($nameControl === $field->getControl()->getName()) {
                unset($this->fields[$k]);
                break;
            }
        }
        return $this;
    }

    private function makeCaption(?FormControl $formControl, ?string $caption): ?string
    {
        if ($caption) {
            return $caption;
        }

        $name = $formControl->getName();

        return $this->captions[$name] ?? null;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param FormControlRenderInterface $field
     * @return DataTableEngine
     */
    public function addFilter(FormControlRenderInterface $field, $caption = null): DataTableEngine
    {
        $this->filters[] = (new FilterField($field))->setCaption($caption);
        return $this;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @param FormControlRenderInterface $control
     * @param array $calback
     * @param string $caption
     * @return DataTableEngine
     */
    public function addAction(FormControlRenderInterface $control, array $calback, $caption = null): DataTableEngine
    {

        $this->actions[] = (new ActionTable($control, $calback))->setCaption($caption);
        return $this;
    }

    /**
     * @param FormControlRenderInterface $control
     * @deprecated
     * @see DataTableEngine::addAction()
     * @param array $calback
     * @param null $caption
     * @return DataTableEngine
     */
    public function addRowAction(FormControlRenderInterface $control, array $calback, $caption = null): DataTableEngine
    {
        $this->rowActions[] = (new ActionTable($control, $calback))->setCaption($caption);
        return $this;
    }

    /**
     * @return array
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @param array $actions
     * @return DataTableEngine
     */
    public function setActions(array $actions): DataTableEngine
    {
        $this->actions = $actions;
        return $this;
    }


    /**
     * @return array
     */
    public function getRowActions(): array
    {
        return array_reverse($this->rowActions);
    }

    /**
     * @param array $rowActions
     * @return DataTableEngine
     */
    public function setRowActions(array $rowActions): DataTableEngine
    {
        $this->rowActions = $rowActions;
        return $this;
    }

    /**
     * очистка буфера вывода
     * @return DataTableEngine
     */
    public function clearContent(): DataTableEngine
    {
        $this->getOutput()->clear();
        return $this;
    }

    /**
     * Добавление контента
     * @param $content
     * @return DataTableEngine
     */
    public function addContent(string $content): DataTableEngine
    {
        $this->getOutput()->addContent($content);
        return $this;
    }

    /**
     * @param string $actionName
     * @param array $calback
     * @return DataTableEngine
     */
    public function addRowActionTable(ActionTable $actionTable): DataTableEngine
    {
        $this->rowActions[] = $actionTable;
        return $this;
    }

    /**
     * @param string $triggerName
     * @param CommandInterface $command
     * @param string $caption
     */
    final public function addRowActionCommand(string $triggerName, CommandInterface $command, string $caption): self
    {

        $this
            ->setCommand($triggerName, $command)
            ->addRowActionTable(
                (new ActionTable(
                    new \Core\Form\Control\Submit($triggerName, $triggerName), []
                ))->setCaption($caption)
            );

        return $this;
    }

    /**
     * @param string $triggerName
     * @param CommandInterface $command
     * @return DataTableEngine
     */
    public function setCommand(string $triggerName, CommandInterface $command): self
    {
        $this
            ->getCommandFactory()
            ->addCommand($triggerName, $command);
        return $this;
    }

    /**
     * @return string
     */
    public function getFormClass(): string
    {
        return $this->formClass;
    }

    /**
     * @param string $formClass
     * @return DataTableEngine
     */
    public function setFormClass(string $formClass): DataTableEngine
    {
        $this->formClass = $formClass;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFieldsWidth()
    {
        return $this->fieldsWidth;
    }

    /**
     * @param mixed $fieldsWidth
     * @return DataTableEngine
     */
    public function setFieldsWidth($fieldsWidth): DataTableEngine
    {
        $this->fieldsWidth = $fieldsWidth;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFormTemplate(): ?string
    {
        return $this->formTemplate;
    }

}