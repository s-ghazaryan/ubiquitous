<?php

namespace App\Database\Repositories;

use Doctrine\ORM\EntityRepository;
use App\Services\Api\Json\JsonApiRequestGuru;

class CategoryRepository extends EntityRepository
{
	use RepositoryTrait;

	public function findCategories(JsonApiRequestGuru $jsonApiRequestGuru)
	{
		$aliace = 'c';
		$qb = $this->createQueryBuilder($aliace);
		// Sorting categories
		$this->addOrderFields($jsonApiRequestGuru, $qb, $aliace);
		// Pagination
		$this->paginate($jsonApiRequestGuru, $qb);	
		
		return $qb->getQuery()
				->getResult();
	}
}