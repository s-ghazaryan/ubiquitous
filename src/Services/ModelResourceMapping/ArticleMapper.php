<?php

namespace App\Services\ModelResourceMapping;

use App\Database\Entities\EntityCollection;

class ArticleMapper extends MapperAncestor
{
	/**
	 * {@inheritDoc}
	 */
	protected $entityNamespace = EntityCollection::ARTICLE;
}