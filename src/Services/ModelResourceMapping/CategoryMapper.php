<?php

namespace App\Services\ModelResourceMapping;

use App\Database\Entities\EntityCollection;

class CategoryMapper extends MapperAncestor
{
	/**
	 * {@inheritDoc}
	 */
	protected $entityNamespace = EntityCollection::CATEGORY;
}