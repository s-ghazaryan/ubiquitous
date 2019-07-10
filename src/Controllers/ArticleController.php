<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation;
use App\Database\Connection;
use App\Database\Entities\Article;
use App\Services\Api\Json\JsonApiRequestGuru;
use App\Services\Api\Json\Resource;
use App\Services\Factory\Factory;
use App\Services\ModelResourceMapping\MapperCollection;

class ArticleController
{
	public function getArticles(HttpFoundation\Request $request, array $placeholders)
	{
		$jsonApiRequestGuru = new JsonApiRequestGuru($request);

		$entityManager = Connection::getEntityManager();
		$articleRepo = $entityManager->getRepository(Article::class);
		$articles = $articleRepo->findArticles($jsonApiRequestGuru);

		$factory = new Factory();
		$mapper = $factory->create($articles, MapperCollection::getConstants());

		$resource = new Resource();
		if ($mapper) {
			$resource = $mapper->populateResource($articles, $jsonApiRequestGuru);
		}

		$response = new HttpFoundation\Response();
		$response->setContent(json_encode($resource->assemble()));
		// TODO: based on returned resource status code need to be defined!
		$response->setStatusCode(200);
		$response->headers->set('Content-Type', 'application/vnd.api+json');

		return $response;
	}

	public function getArticle(HttpFoundation\Request $request, array $placeholders)
	{
		$jsonApiRequestGuru = new JsonApiRequestGuru($request);

		$entityManager = Connection::getEntityManager();
		$articleRepo = $entityManager->getRepository(Article::class);
		$article = $articleRepo->find($placeholders['id']);

		$factory = new Factory();
		$mapper = $factory->create($article, MapperCollection::getConstants());

		$resource = new Resource();
		if ($mapper) {
			$resource = $mapper->populateResource($article, $jsonApiRequestGuru);
		}

		$response = new HttpFoundation\Response();
		$response->setContent(json_encode($resource->assemble()));
		// TODO: based on returned resource status code need to be defined!
		$response->setStatusCode(200);
		$response->headers->set('Content-Type', 'application/vnd.api+json');

		return $response;
	}
}
