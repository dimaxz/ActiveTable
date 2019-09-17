<?php

namespace src_\Concrete;

use src_\Contracts\PaginationRenderInterface;
use src_\Contracts\TableActionInterface;
use src_\Contracts\TableRenderingInterface;
use src_\Contracts\ActionRequestInterface;
use src_\Contracts\TableSortingInterface;

/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 22.01.2019
 * Time: 16:31
 */

class EasyTable implements  TableRenderingInterface, TableSortingInterface {

	protected $sortedFields = [];
	protected $name;
	protected $baseUrl;
	protected $actionRequest;
	protected $pagination;
	protected $class = "easy_form";


	function __construct($name, TableActionInterface $actionRequest) {
		$this->name = $name;
		$this->actionRequest = $actionRequest;
		$this->baseUrl = $actionRequest->getBaseUrl();
		$this->pagination = new EmptyPagination();
	}

	/**
	 * @param string $class
	 *
	 * @return $this
	 */
	public function setClass(string $class){
		$this->class = $class;
		return $this;
	}

	/**
	 * реализация навигации
	 * @param PaginationRenderInterface $pagination
	 *
	 * @return $this
	 */
	public function setPagination(PaginationRenderInterface $pagination){
		$this->pagination = $pagination;
		return $this;
	}

	/**
	 * получить сортируемые поля
	 * @return array
	 */
	public function setSortedFields(array $fields):EasyTable{
		$this->sortedFields = $fields;
		return $this;
	}

	/**
	 * @param array $rows
	 */
	public function renderBody(array $rows): string{

		$html = "";

		foreach($rows as $row){
			$html .= "<tr><td>" . implode("</td><td>", $row) . "</td></tr>";
		}

		return $html;
	}


	/**
	 * @param array $rows
	 *
	 * @return string
	 */
	public function renderHeader(array $rows): string {

		$sorted = $this->sortedFields;
		$url = $this->baseUrl;

		$html 	= "<tr>";
		foreach($rows as $key => $row){
			$html .= "<th>";

			if(isset($sorted[$key]) && strtolower($sorted[$key]) == "asc"){
				$html .= sprintf("<a href=\"%s\" title=\"Сортировать по возрастанию\" ><font style=\"color:red\">%s</font></a>", $url. "?" . http_build_query([
					"sort_by_" . $key => "desc"
					]), "^");
			}
			elseif(isset($sorted[$key])&& strtolower($sorted[$key]) == "desc"){
				$html .= sprintf("<a href=\"%s\" title=\"Сортировать по убыванию\" ><font style=\"color:red\">%s</font></a>", $url . "?" . http_build_query([
					"sort_by_" . $key => "asc"
				]), "v");
			}
			elseif(isset($sorted[$key])){
				$html .= sprintf("<a href=\"%s\" title=\"Сортировать по убыванию\" >%s</a>", $url . "?" . http_build_query([
						"sort_by_" . $key => "desc"
					]), "^");
			}

			$html .= $row;
			$html .= "</th>";
		}
		$html .= "</tr>";

		return $html;
	}

	/**
	 * @return string
	 */
	public function renderTop(array $rows): string {

        $html = "";
        
	    if(count($rows)){
            $html = sprintf("<form action='%s' name='%s_filter' type='get'><table>", $this->baseUrl, $this->name);
            foreach ($rows as $k => $row){
                $html .= "<tr>" . sprintf("<td><label for='%s' >%s</label></td><td>%s</td>",$k,$row[0],$row[1]) . "</tr>" ;
            }
            $html .= "</table>";
            $html .= sprintf(
                "<br/><button type='submit' >Фильтровать</button>".
                "<button onclick=\"location.href = '%s';return false;\"/>Сбросить</button>",
                $this->baseUrl
            );
        }

		return $html .
			$this->pagination->render() .
			sprintf("<table class='%s'>",$this->class)
			;
	}

	/**
	 * @return string
	 */
	public function renderBottom(): string {
		return  "</table>" . $this->pagination->render();
	}

}