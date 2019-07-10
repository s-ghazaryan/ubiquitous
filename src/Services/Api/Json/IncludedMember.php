<?php

namespace App\Services\Api\Json;

class IncludedMember
{
	public static function getMethodsToExtractRelationships($entity, JsonApiRequestGuru $jsonApiRequestGuru): array
	{
		$relationshipsToInclude = $jsonApiRequestGuru->prepareIncludeParams();

		$getters = $entity->getRelationshipsGetters();
		foreach ($getters as $relationshipName => $getter) {
			if (!in_array($relationshipName, $relationshipsToInclude)) {
				unset($getters[$relationshipName]);
			}
		}

		return $getters;
	}
}
