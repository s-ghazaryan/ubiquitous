<?php

namespace App\Services\ModelResourceMapping;

use App\Services\Api\Json\Resource;
use App\Services\Api\Json\JsonApiRequestGuru;

interface MapperInterface
{
	/**
	 * Checks to see whether to use this class or not 
	 * (generally this is meant for Factory class)
	 *
	 * @param mixed $comparable this will usually be an array full of entities
	 *
	 * @return bool
	 */
	public function isUsed($comparable): bool;

	/**
	 * Populates resource based on model (entity) data
	 *
	 * @param entity[]|entity @objectToExtractMappingDataFrom
	 * @param JsonApiRequestGuru $jsonApiRequestGuru based on this object some data will be included
	 *												 and some will be excluded from Resource object.
	 *
	 * @return Resource
	 */
	public function populateResource($objectToExtractMappingDataFrom, JsonApiRequestGuru $jsonApiRequestGuru): Resource;
}