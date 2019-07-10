<?php

namespace App\Database\Repositories;

use Doctrine\ORM\EntityRepository;
use App\Services\Api\Json\JsonApiRequestGuru;

class ArticleRepository extends EntityRepository
{
	use RepositoryTrait;

	public function findArticles(JsonApiRequestGuru $jsonApiRequestGuru)
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