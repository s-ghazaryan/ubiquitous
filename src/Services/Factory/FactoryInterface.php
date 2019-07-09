<?php

namespace App\Services\Factory;

interface FactoryInterface
{
	/**
	 * Creates (initialize) object which is required ($comparable is in charge of decision)
	 *
	 * @param mixed $comparable
	 * @param array $collectionToCreateFrom
	 *
	 * @return mixed
	 */
	public function create($comparable, array $collectionToCreateFrom);
}