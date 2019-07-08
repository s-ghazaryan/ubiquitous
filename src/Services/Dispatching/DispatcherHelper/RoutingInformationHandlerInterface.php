<?php

namespace App\Services\Dispatching\DispatcherHelper;

interface RoutingInformationHandlerInterface
{
	public function getHandler(): array;

	public function setHandler(array $handler);

	public function getPlaceholders(): array;

	public function setPlaceholders(array $placeholders);

	public function getControllerName(): string;

	public function getMethodName(): string;
}
