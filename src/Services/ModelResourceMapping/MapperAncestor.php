<?php

namespace App\Services\ModelResourceMapping;

use App\Services\Api\Json\Resource;
use App\Services\Api\Json\JsonApiRequestGuru;

class MapperAncestor implements MappingInterface
{
	/**
	 * @var string fully qualified namespace
	 */
	protected $entityNamespace;

	/**
	 * {@inheritDoc}
	 */
	public function isUsed($comparable): bool
	{
		if ($this->isIterable($comparable)) {
			foreach ($comparable as $object) {
				if ($object instanceof $this->entity) {
					return true;
				}
			}
		} elseif ($object instanceof $this->entity) {
			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function populateResource($objectToExtractMappingDataFrom, JsonApiRequestGuru $jsonApiRequestGuru): Resource
	{
		// TODO: extract data from $objectToExtractMappingDataFrom and populate Resource object
	}

	private function isIterable($comparable): bool
	{
		return is_array($comparable) || $comparable instanceof \Traversable;
	}
}