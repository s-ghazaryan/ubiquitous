<?php

namespace App\Services\ModelResourceMapping;

class MapperCollection
{
	const ARTICLE_MAPPER = ArticleMapper::class;
	const CATEGORY_MAPPER = CategoryMapper::class;

	public static function getConstants(): array
	{
		$reflection = new \ReflectionClass(self::class);
		return $reflection->getConstants();
	}
}