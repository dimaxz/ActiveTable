<?

namespace ActiveTableEngine\Contracts;

use Repo\PaginationInterface;

/**
 * Комплексный интерфейс включающий в себя и навигацию и слушатель действий
 *
 * @author d.lanec
 */
interface TableActionInterface extends PaginationInterface, ActionRequestInterface
{

	public function isViewRecord(): bool;

	public function isViewForm(): bool;

	public function isDeleteRecord(): bool;

	public function isUpdateRecord() :bool;

	public function isCreateRecord() :bool;

	public function getKey(): int;
}