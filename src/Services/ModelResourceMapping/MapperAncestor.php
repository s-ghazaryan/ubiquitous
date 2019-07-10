<?php

namespace App\Services\ModelResourceMapping;

use App\Services\Api\Json\Resource;
use App\Services\Api\Json\JsonApiRequestGuru;
use App\Services\Api\Json\RelationshipsMember;
use App\Services\Api\Json\IncludedMember;
use App\Services\Api\Json\LinksMember;
use App\Database\Entities\EntityInformationFetchingVirtuoso;


class MapperAncestor implements MapperInterface
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
				if ($object instanceof $this->entityNamespace) {
					return true;
				}
			}
		} elseif ($comparable instanceof $this->entityNamespace) {
			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function populateResource($objectToExtractMappingDataFrom, JsonApiRequestGuru $jsonApiRequestGuru): Resource
	{
		// this object need to be populated and returned to controller!
		$resource = new Resource();

		if ($this->isIterable($objectToExtractMappingDataFrom)) {
			foreach ($objectToExtractMappingDataFrom as $object) {
				$singleResource = new Resource();

				$this->populateLinks($object, $resource, $jsonApiRequestGuru);
				$this->populateIdAndType($object, $singleResource);
				$this->populateAttributes($object, $singleResource, $jsonApiRequestGuru);
				$this->populateRelationships($object, $singleResource);
				$this->populateIncluded($object, $resource, $jsonApiRequestGuru);

				$resource->addResource($singleResource);
			}
		} else {
			$singleResource = new Resource();

			$this->populateIdAndType($objectToExtractMappingDataFrom, $singleResource);
			$this->populateAttributes($objectToExtractMappingDataFrom, $singleResource, $jsonApiRequestGuru);
			$this->populateRelationships($objectToExtractMappingDataFrom, $singleResource);
			$this->populateIncluded($objectToExtractMappingDataFrom, $resource, $jsonApiRequestGuru);

			$resource->addResource($singleResource);
		}

		return $resource;
	}

	protected function isIterable($comparable): bool
	{
		return is_array($comparable) || $comparable instanceof \Traversable;
	}

	protected function populateAttributes($object, Resource $singleResource, JsonApiRequestGuru $jsonApiRequestGuru): Resource
	{
		// TODO: Remove this dependencies with DependencyInjection component.
		$entityInformationFetchingVirtuoso = new EntityInformationFetchingVirtuoso();
		$singleResource->setAttributes($entityInformationFetchingVirtuoso->getData($object));

		return $singleResource;
	}

	protected function populateIdAndType($object, Resource $singleResource): Resource
	{
		$singleResource->setId($object->getId());
		$singleResource->setType($object->getTypeForJsonApi());

		return $singleResource;
	}

	protected function populateRelationships($object, Resource $singleResource): Resource
	{
		$relationshipsMember = new RelationshipsMember($object);
		$singleResource->setRelationships($relationshipsMember->getRelationships());

		return $singleResource;
	}

	protected function populateIncluded($object, Resource $resource, JsonApiRequestGuru $jsonApiRequestGuru): Resource
	{
		$getters = IncludedMember::getMethodsToExtractRelationships($object, $jsonApiRequestGuru);

		foreach ($getters as $getter) {
			$collectionOfRelatedEntity = $object->$getter();

			foreach ($collectionOfRelatedEntity as $relatedEntity) {
				$includedResource = new Resource();

				$this->populateIdAndType($relatedEntity, $includedResource);
				$this->populateAttributes($relatedEntity, $includedResource, $jsonApiRequestGuru);
				$this->populateRelationships($relatedEntity, $includedResource);

				$resource->addToIncluded($includedResource);
			}
		}

		return $resource;
	}

	protected function populateLinks($object, Resource $resource, JsonApiRequestGuru $jsonApiRequestGuru): Resource
	{
		$links = LinksMember::preparePaginationLinks($object, $jsonApiRequestGuru);
		$resource->setPrimaryDataLinks($links);

		return $resource;
	}
}
