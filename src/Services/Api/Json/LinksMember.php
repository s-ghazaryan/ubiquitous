<?php

namespace App\Services\Api\Json;

use App\Database\Connection;
use Symfony\Component\HttpFoundation\Request;

class LinksMember
{
	public static function getSelfLink($entity)
	{
		$type = $entity->getTypeForJsonApi();
		$id = $entity->getId();

		return '/' .$type. '/' .$id;
	}

	public static function preparePaginationLinks($entity, JsonApiRequestGuru $jsonApiRequestGuru): array
	{
		$links = [];

		$pageSize = $jsonApiRequestGuru->getPageSize();
		$pageNumber = $jsonApiRequestGuru->getPageNumber();

		if ($pageNumber && $pageSize) {
			$entityManager = Connection::getEntityManager();
			$amountOfRecords = $entityManager->getRepository($entity->getSelf())->count([]);
			// last page
			$totalPages = round($amountOfRecords / $pageSize);

			$request = $jsonApiRequestGuru->getRequest();
			
			$links[Members::SELF_MEMBER] = $request->getUri();
			// previouse page link
			$links[Members::FIRST] = self::getPaginationLink(Members::FIRST, $request, $totalPages);
			$links[Members::LAST] = self::getPaginationLink(Members::LAST, $request, $totalPages);
			$links[Members::PREV] = self::getPaginationLink(Members::PREV, $request, $totalPages);
			$links[Members::NEXT] = self::getPaginationLink(Members::NEXT, $request, $totalPages);
		}

		return $links;
	}

	public static function getPaginationLink(string $pagePosition, Request $request, int $lastPage): string
	{
		$queryParams = $request->query->all();
		$pageNumber = $queryParams['page']['number'];

		if ($pagePosition === Members::FIRST) {
			$pageNumber = 1;
		} elseif ($pagePosition === Members::PREV) {
			if ($pageNumber > 1) {
				$pageNumber--;
			}
		} elseif ($pagePosition === Members::NEXT) {
			if ($pageNumber < $lastPage) {
				$pageNumber++;
			}
		} elseif ($pagePosition === Members::LAST) {
			$pageNumber = $lastPage;
		}

		$queryParams['page']['number'] = $pageNumber;
		$queryToString = http_build_query($queryParams);

		return $request->getSchemeAndHttpHost(). $request->getPathInfo().  '?' .$queryToString;
	}
}