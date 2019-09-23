<?php

namespace ActiveTable;

use ActiveTable\Contracts\CommandFactoryInterface;
use ActiveTable\Contracts\ControlRenderInterface;
use ActiveTable\Contracts\FormControlRenderInterface;
use ActiveTable\EmptyControls\Content;
use ActiveTable\EmptyControls\TableAction;
use ActiveTable\EmptyControls\TableBottomControl;
use ActiveTable\EmptyControls\TableControl;
use ActiveTable\EmptyControls\TableFilter;
use ActiveTable\EmptyControls\TableRowAction;
use ActiveTable\EmptyControls\TableTopControl;
use Infrastructure\ActiveTable\Submit;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Repo\CrudRepositoryInterface;
use Repo\PaginationInterface;
use Repo\RepositoryCriteriaInterface;

class DataTableEngine
{

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
     * контролы для выборочных записей таблицы, активирют чекбоксы
     * @var array
     */
    protected $rowActions = [];

    protected $class;

    /**
     * @var Content
     */
    protected $output;

    protected $request;

    /**
     * касмтомный перечень контролов по умолчанию
     * @var array
     */
    protected $controlAccess = [];

    /**
     * критерия выборки из репо нужна для навигации фильтрации и тд. по сути с ним только работает репозиторий
     * @var PaginationInterface
     */
    protected $criteria;

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
    public function render(): string
    {

        $this->commandFactory->build($this)->process();

        return $this->output->getContent();
    }

    /**
     * @param string $name
     * @return DataTableEngine
     */
    public function addControlAccess(string $name): DataTableEngine{
        if(!in_array($name, $this->controlAccess)){
            $this->controlAccess []= $name;
        }

        return $this;
    }

    /**
     * @param array $names
     * @return DataTableEngine
     */
    public function setControlAccess(array $names): DataTableEngine{
        $this->controlAccess = $names;
        return $this;
    }

    /**
     * @param string $name
     * @return DataTableEngine
     */
    public function removeControlAccess(string $name): DataTableEngine{
        if($key = array_search($this->controlAccess)){
            unset($this->controlAccess[$key]);
        }

        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasControlAccess(string $name): bool {
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
        return $this;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return CrudRepositoryInterface
     */
    public function getRepo(): CrudRepositoryInterface
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
     * @deprecated
     * @see self::clearContent
     * @see self::addContent
     * @return Content
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

    /**
     * @param FormControlRenderInterface $field
     */
    public function addField(FormControlRenderInterface $field, $require  = false, $caption = null): DataTableEngine{
        $this->fields[] = (new FormField($field))->setRequire($require)->setCaption($caption);
        return $this;
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
    public function addFilter(FormControlRenderInterface $field, $caption = null): DataTableEngine{
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
    public function addAction(FormControlRenderInterface $control, array $calback, $caption = null): DataTableEngine{

        $this->actions[]= (new ActionTable($control,$calback))->setCaption($caption);
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
     * @param FormControlRenderInterface $control
     * @param array $calback
     * @param null $caption
     * @return DataTableEngine
     */
    public function addRowAction(FormControlRenderInterface $control, array $calback, $caption = null): DataTableEngine{
        $this->rowActions[]= (new ActionTable($control,$calback))->setCaption($caption);
        return $this;
    }

    /**
     * @return array
     */
    public function getRowActions(): array
    {
        return $this->rowActions;
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
    public function clearContent(): DataTableEngine{
        $this->getOutput()->clear();
        return $this;
    }

    /**
     * Добавление контента
     * @param $content
     * @return DataTableEngine
     */
    public function addContent(string $content): DataTableEngine{
        $this->getOutput()->addContent($content);
        return $this;
    }

    /**
     * @param string $actionName
     * @param array $calback
     * @return DataTableEngine
     */
    public function addRowActionHandler(string $actionName,array $calback):DataTableEngine  {
        $this->rowActions[]= (new ActionTable(
            new Submit($actionName, $actionName)
            ,$calback));
        return $this;
    }

}