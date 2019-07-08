<?php

namespace App\Services\Dispatching\DispatcherHelper;

class RoutingInformationHandler implements RoutingInformationHandlerInterface
{
	/**
	 * @var string[]	
	 */
	private $handler;

	/**
	 * @var array
	 */
	private $placeholders;

	public function __construct(array $handler, array $placeholders)
	{
		$this->handler = $handler;
		$this->placeholders = $placeholders;
	}

	public function getHandler(): array
	{
		return $this->handler;
	}

	public function setHandler(array $handler)
	{
		$this->handler = $handler;
		return $this;
	}

	public function getPlaceholders(): array
	{
		return $this->placeholders;
	}

	public function setPlaceholders(array $placeholders)
	{
		$this->placeholders = $placeholders;
		return $this;
	}

	public function getControllerName(): string
	{
		return $this->handler[0];
	}

	public function getMethodName(): string
	{
		return $this->handler[1];
	}
}