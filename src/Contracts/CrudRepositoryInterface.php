<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 23.01.2019
 * Time: 17:13
 */

namespace ActiveTableEngine\Contracts;

use Mapper\AbstractCollection;
use Mapper\AbstractEntity;

interface CrudRepositoryInterface {

	public function findByCriteria(PaginationInterface $criteria): AbstractCollection;

	public function count(): int;

	public function save(AbstractEntity $entity);

	public function findById(int $id): ?AbstractEntity;

	public function delete(AbstractEntity $entity);

	public static function createEntity():AbstractEntity;

	public static function buildEntityFromArray(array $row);
}