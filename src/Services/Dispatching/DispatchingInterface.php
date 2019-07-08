<?php

namespace App\Services\Dispatching;

use Symfony\Component\HttpFoundation; 
use App\Services\Dispatching\DispatcherHelper\RoutingInformationHandlerInterface;

interface DispatchingInterface
{
	public function dispatch(HttpFoundation\Request $request, RoutingInformationHandlerInterface $routingInformationHandler): HttpFoundation\Response;
}