<?php

namespace App\Database\Entities;

class EntityInformationFetchingVirtuoso
{
	public function getData($object): array
	{
		$dataToReturn = [];

		$arrayOfFieldNames = $object->getConstants();
		$getters = $this->prepareGetters($arrayOfFieldNames);

		foreach ($getters as $fieldName => $getter) {
			$dataToReturn[$fieldName] = $object->$getter();
		}

		return $dataToReturn;
	}

	private function prepareGetters(array $arrayOfFieldNames): array
	{
		$getters = [];
		foreach ($arrayOfFieldNames as $fieldName) {
			$getters[$fieldName] = 'get' .$this->underscoreToCamelCase($fieldName);
		}

		return $getters;
	}

	private function underscoreToCamelCase($string, $capitalizeFirstCharacter = true) 
	{

	    $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

	    if (!$capitalizeFirstCharacter) {
	        $str[0] = strtolower($str[0]);
	    }

	    return $str;
	}
}
