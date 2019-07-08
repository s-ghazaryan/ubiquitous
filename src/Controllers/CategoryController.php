<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation;
use App\Database\Connection;
use App\Database\Entities\Article;

class CategoryController
{
	public function get(HttpFoundation\Request $request, array $placeholders)
	{
		$entityManager = Connection::getEntityManager();
		$article = $entityManager->find(Article::class, 1);

		echo $article->getTitle();
		die;

		return HttpFoundation\Response::create('Test');
	}	
}
