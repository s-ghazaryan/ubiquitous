<?php

namespace App\Services\Factory;

class Factory implements FactoryInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function create($comparable, array $collectionToCreateFrom)
	{
		foreach ($collectionToCreateFrom as $classNamespace) {
			$object = new $classNamespace();
			if ($object->isUsed($comparable)) {
				return $object;
			}
		}

		return null;
	}
}