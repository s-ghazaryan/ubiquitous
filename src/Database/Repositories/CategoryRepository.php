<?php

namespace App\Database\Repositories;

use Doctrine\ORM\EntityRepository;
use App\Services\Api\Json\JsonApiRequestGuru;

class CategoryRepository extends EntityRepository
{
	public function findCategories(JsonApiRequestGuru $jsonApiRequestGuru)
	{
		$qb = $this->createQueryBuilder('c');

		// Sorting categories
		$sortParams = $jsonApiRequestGuru->prepareSortParams();
		foreach ($sortParams as $sortParam => $sortOrder) {
			if ($sortOrder) {
				$sortOrder = 'ASC';
			} else {
				$sortOrder = 'DESC';
			}

			$qb->orderBy($sortParam, $sortOrder);
		}

		$qb->getQuery;
		
		return $qb->execute();

	}
}