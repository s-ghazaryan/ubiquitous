<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation;
use App\Database\Connection;
use App\Database\Entities\Category;
use App\Services\Api\Json\JsonApiRequestGuru;
use App\Services\Api\Json\Resource;
use App\Services\Factory\Factory;
use App\Services\ModelResourceMapping\MapperCollection;

class CategoryController
{
	public function getCategories(HttpFoundation\Request $request, array $placeholders)
	{
		$jsonApiRequestGuru = new JsonApiRequestGuru($request);

		$entityManager = Connection::getEntityManager();
		$categoryRepo = $entityManager->getRepository(Category::class);
		$categories = $categoryRepo->findCategories($jsonApiRequestGuru);

		$factory = new Factory();
		$mapper = $factory->create($categories, MapperCollection::getConstants());

		$resource = new Resource();
		if ($mapper) {
			$resource = $mapper->populateResource($categories, $jsonApiRequestGuru);
		}

		$response = new HttpFoundation\Response();
		$response->setContent(json_encode($resource->assemble()));
		// TODO: based on returned resource status code need to be defined!
		$response->setStatusCode(200);
		$response->headers->set('Content-Type', 'application/vnd.api+json');

		return $response;
	}

	public function getCategory(HttpFoundation\Request $request, array $placeholders)
	{
		$jsonApiRequestGuru = new JsonApiRequestGuru($request);

		$entityManager = Connection::getEntityManager();
		$categoryRepo = $entityManager->getRepository(Category::class);
		$category = $categoryRepo->find($placeholders['id']);

		$factory = new Factory();
		$mapper = $factory->create($category, MapperCollection::getConstants());

		$resource = new Resource();
		if ($mapper) {
			$resource = $mapper->populateResource($category, $jsonApiRequestGuru);
		}

		$response = new HttpFoundation\Response();
		$response->setContent(json_encode($resource->assemble()));
		// TODO: based on returned resource status code need to be defined!
		$response->setStatusCode(200);
		$response->headers->set('Content-Type', 'application/vnd.api+json');

		return $response;
	}
}
