<?

namespace ActiveTableEngine\Concrete\AutoResource;

use ActiveTableEngine\Contracts\CrudRepositoryInterface;
use ActiveTableEngine\Contracts\PaginationInterface;
use ActiveTableEngine\Contracts\PaginationRenderInterface;
use DigitalTextBox,
	cLink;

/**
 * Description of Pagination
 *
 * @author d.lanec
 */
class Pagination implements PaginationRenderInterface {

	protected $navigation;

	protected $msgPagesTitle = "Страницы:";

	protected $instanceCount = 1;

	protected $allRows;

	protected $currentPage = 1;
	
	protected $rows = 50;
	
	protected $defaultRows = [50,100,200];

	protected $repo;
	protected $action;
	const LIMIT_FIELD = "rows";
	const PAGE_FIELD = "page";

	public function __construct(CrudRepositoryInterface $repo,  PaginationInterface $action) {
		$this->repo 	= $repo;
		$this->action 	= $action;
	}

	/**
	 * @param $rows
	 *
	 * @return $this
	 */
	function  setRows($rows){
		$this->rows = $rows;
		return $this;
	}
	
	function setDefaultRows($defaultRows)
	{
		$this->defaultRows = $defaultRows;
		return $this;
	}


	function setAllRows($lastPage) {
		$this->allRows = $lastPage;
		return $this;
	}

	public function render() : string {

		$this->allRows = $this->repo->count();

		$this->rows = $this->action->getLimit() === 0 ? $this->action->getDefaultLimit() : $this->action->getLimit();
		$this->currentPage = $this->action->getPage() > 1?$this->action->getPage():1;

		return sprintf(
			'<div class="paginator flc">%s%s</div>',
			$this->renderRowsControl(),
			($this->allRows > $this->action->getLimit() ? $this->renderPagination()  : "")
		);
	}
	
	/**
	 * 
	 * @return string
	 */
	protected function renderPagination() {
		$paginator = '';

		$isLastPage	= $isLastPage = ($this->allRows <= $this->currentPage * $this->rows);

		$lastPage = ceil($this->allRows / $this->rows);
		
		
		$page_num = new DigitalTextBox("noname", (int) $this->currentPage);
		$page_num->bindEvent("onfocus", "select(this);");

		$paginator .= '<div class="paginator_pages_title">' . $this->msgPagesTitle . '</div>';

		$newPageLink = new cLink($_SERVER['REQUEST_URI'], "");
		$newPageLink->removeQueryParam(self::PAGE_FIELD);

		if (strpos($newPageLink->link, '?')) {
			$separator = "&";
		} else {
			$separator = "?";
		}

		unset($page_num->events["onkeypress"]);
		$page_num->bindEvent("onkeypress",
				"
					if (event.keyCode==13) {
						window.location.href='" . $newPageLink->link . $separator . self::PAGE_FIELD . "='+this.value;
						return false;
					}
					return digitsCheck(event);
					"
		);


		$newPageLink2 = new cLink($_SERVER['REQUEST_URI'], "");

		$newPageLink2->removeQueryParam(self::PAGE_FIELD);
		$newPageLink2->addQueryParam(self::PAGE_FIELD, "");

		$paginator .= '
						<div class="paginator_pages">
							' . ((int) $this->currentPage != 1 ? '<a href="' . $newPageLink2->link . '1" class="page_first"><span></span></a>' : '') . '
							' . ((int) $this->currentPage > 1 ? '<a href="' . $newPageLink2->link . ((int) $this->currentPage - 1) . '" class="page_prev"><span></span></a>' : '') . '
							' . $page_num->render(new \HTML_DataRender()) . '
							' . (!$isLastPage ? '<a href="' . $newPageLink2->link . ((int) $this->currentPage + 1) . '" class="page_next"><span></span></a>' : '') . '
							' . (!$isLastPage ? '<a href="' . $newPageLink2->link . $lastPage .'" class="page_last"><span></span></a>' : '') . '
						</div>
					';

		return $paginator;		
	}
	
	/**
	 * 
	 * @return string
	 */
	protected function renderRowsControl() {
		$paginator = '<div class="paginator_caption">' . 'Показывать по' . '</div>';

		$newLink = new cLink($_SERVER['REQUEST_URI'], "");
		
		$newLink->removeQueryParam(self::LIMIT_FIELD);
		$newLink->addQueryParam(self::LIMIT_FIELD, "");

		js("
						function setRPP(pg_count) {
							setCookie('pg_count',pg_count);
							window.location.href = '" . $newLink->link . "'+pg_count
						}
						
					");

		$paginator .= '<div class="pagination_buttons flc">';

		$pg_count = ($_REQUEST[self::LIMIT_FIELD] != "" ? $_REQUEST[self::LIMIT_FIELD] : $this->rows);
		foreach ($this->defaultRows  as $pg_value) {
			$paginator .= '<a href="#" onclick="setRPP(\'' . $pg_value . '\')" class="paginator_rpp_button' . ($pg_value == $pg_count ? ' paginator_rpp_button_active' : '') . '"><span>' . $pg_value . '</span></a>';
		}
		$paginator .= '</div>';
		
		return $paginator;
	}	

}
