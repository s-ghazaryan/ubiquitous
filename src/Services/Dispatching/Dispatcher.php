<?php

namespace App\Services\Dispatching;

use Symfony\Component\HttpFoundation;
use App\Services\NamespaceHub\NamespaceNerd;
use App\Services\Dispatching\DispatcherHelper\RoutingInformationHandlerInterface;

class Dispatcher implements DispatchingInterface
{
	public function dispatch(HttpFoundation\Request $request, RoutingInformationHandlerInterface $routingInformationHandler): HttpFoundation\Response
	{
		$controller = $this->instantiateController($routingInformationHandler);
		$methodName = $routingInformationHandler->getMethodName();
		$placeholders = $routingInformationHandler->getPlaceholders();

		return $controller->$methodName($request, $placeholders);
	}

	// TODO define controller interface
	private function instantiateController(RoutingInformationHandlerInterface $routingInformationHandler)
	{
		$controllerName = $routingInformationHandler->getControllerName();
		$controllerFullyQualifiedNamesapce = NamespaceNerd::CONTROLLERS_NAMESPACE.$controllerName;

		return new $controllerFullyQualifiedNamesapce();
	}
}
