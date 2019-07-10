<?php

namespace App\Middlewares;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Services\Dispatching\DispatcherHelper\RoutingInformationHandler;
use App\Services\Dispatching\Dispatcher;
use FastRoute;

class RoutingMiddleware implements MiddlewareInterface
{
	/**
	 * @var MiddlewareInterface|null
	 */
	private $next = null;

	public function process(Request $request): Response
	{
		$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
		    $r->addRoute('GET', '/categories', ['CategoryController', 'getCategories']);
		    $r->addRoute('GET', '/categories/{id}', ['CategoryController', 'getCategory']);
		    $r->addRoute('GET', '/articles', ['ArticleController', 'getArticles']);
		    $r->addRoute('GET', '/articles/{id}', ['ArticleController', 'getArticle']);
		});

		$httpMethod = $request->getMethod();
		$uri = $request->getBaseUrl().$request->getPathInfo();
		$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

		switch ($routeInfo[0]) {
		    case FastRoute\Dispatcher::NOT_FOUND:
		        // ... 404 Not Found
		        break;
		    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
		        $allowedMethods = $routeInfo[1];
		        // ... 405 Method Not Allowed
		        break;
		    case FastRoute\Dispatcher::FOUND:
				// dispatcher hadnling preparation						        
		        $handler = $routeInfo[1];
		        $placeholders = $routeInfo[2];
		        $routingInformationHandler = new RoutingInformationHandler($handler, $placeholders);
		        
		        $dispatcher = new Dispatcher();
		        return $dispatcher->dispatch($request, $routingInformationHandler);
		        break;
		}
	}

	public function addNext(MiddlewareInterface $middelware): void
	{
		$this->next = $middelware;
	}
}
