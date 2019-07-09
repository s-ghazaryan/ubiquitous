<?php

namespace App\Database\Entities;

class EntityCollection
{
	const ARTICLE = Article::class;
	const CATEGORY = Category::class;

	public function getConstants(): array
	{
		$reflection = new \ReflectionClass(self::class);
		return $reflection->getConstants();
	}
}