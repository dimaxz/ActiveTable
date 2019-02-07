<?

namespace ActiveTableEngine\Contracts;

/**
 * Description of PaginationInterface
 *
 * @author d.lanec
 */
interface PaginationInterface
{
	/**
	 * получение страницы
	 */
	public function getPage(): int;
	
	/**
	 * получение лимита
	 */
	public function getLimit(): int;

	/**
	 * @param int $page
	 *
	 * @return mixed
	 */
	public function setPage(int $page);

	/**
	 * @param int $limit
	 *
	 * @return mixed
	 */
	public function setLimit(int $limit);

	/**
	 * @return int
	 */
	public function getDefaultLimit(): int;
}