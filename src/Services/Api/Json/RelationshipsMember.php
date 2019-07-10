<?php

namespace App\Services\Api\Json;

use Symfony\Component\HttpFoundation\ParameterBag;

class RelationshipsMember
{
	/**
	 * all relationships will be stored here
	 *
	 * @var array
	 */
	private $relationships = [];

	/**
	 * @param $entity interface of this class is in TODO state. 
	 */
	public function __construct($entity)
	{
		$relationshipsGetters = $entity->getRelationshipsGetters();

		if (!empty($relationshipsGetters)) {
			foreach ($relationshipsGetters as $relationshipGetter) {
				$collectionOfEntities = $entity->$relationshipGetter();
				$this->relationships[$this->getRelationshipObject($collectionOfEntities)->getTypeForJsonApi()] = $this->prepareRelationship($collectionOfEntities, $entity);
			}
		}
	}

	public function prepareRelationship($collectionOfEntities, $entity): array
	{
		$relationship = [];
		$relationship[Members::DATA] = $this->prepareData($collectionOfEntities, $entity);
		$relationship[Members::LINKS] = $this->prepareLinks($collectionOfEntities, $entity);

		return $relationship;
	}

	private function prepareData($collectionOfEntities, $entity): array
	{
		$data = [];
		foreach ($collectionOfEntities as $entity) {
			$singleEntityData = [];
			$singleEntityData[Members::ID] = $entity->getId();
			$singleEntityData[Members::TYPE] = $entity->getTypeForJsonApi();

			$data[] = $singleEntityData;
		}

		return $data;
	}

	private function prepareLinks($collectionOfEntities, $entity): array
	{
		$links = [];

		$relationshipObject = $this->getRelationshipObject($collectionOfEntities);
		$links[Members::SELF_MEMBER] = LinksMember::getSelfLink($entity). '/relationships/' .$relationshipObject->getTypeForJsonApi();
		$links[Members::RELATED] = LinksMember::getSelfLink($entity). '/' .$relationshipObject->getTypeForJsonApi();

		return $links;
	}	

	private function getRelationshipObject($collectionOfEntities)
	{
		return $collectionOfEntities[0];
	}

	public function getRelationships(): array
	{
		return $this->relationships;
	}
}
