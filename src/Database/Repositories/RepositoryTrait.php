<?php

namespace App\Database\Repositories;

use App\Services\Api\Json\JsonApiRequestGuru;
use Doctrine\ORM\QueryBuilder;

trait RepositoryTrait
{
	protected function addOrderFields(JsonApiRequestGuru $jsonApiRequestGuru, QueryBuilder $queryBuilder, string $aliace): QueryBuilder
	{
		$sortParams = $jsonApiRequestGuru->prepareSortParams();
		foreach ($sortParams as $sortParam => $sortOrder) {
			if ($sortOrder) {
				$sortOrder = 'ASC';
			} else {
				$sortOrder = 'DESC';
			}

			$queryBuilder->addOrderBy($aliace. '.' .$sortParam, $sortOrder);
		}

		return $queryBuilder;
	}

	protected function paginate(JsonApiRequestGuru $jsonApiRequestGuru, QueryBuilder $queryBuilder): QueryBuilder
	{
		$pageNumber = $jsonApiRequestGuru->getPageNumber();
		$pageSize = $jsonApiRequestGuru->getPageSize();

		if ($pageNumber && $pageSize) {
			// offset calculation
			$amountOfRecords = parent::count([]);
			// if page number is not positive integer then offset will be a zero.
			$offset = $pageSize * ($pageNumber > 0 ? $pageNumber - 1 : 0);

			$queryBuilder->setMaxResults($pageSize);
			$queryBuilder->setFirstResult($offset);
		}

		return $queryBuilder;
	}
}
