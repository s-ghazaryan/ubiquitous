<?php

namespace App\Middlewares;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class LayeredMiddlewaresHub
{
	/**
	 * @var MiddlewareInterface[]
	 */
	private $layers = [];

	public function isEmpty(): bool
	{
		if (empty($this->layers)) {
			return true;
		}

		return false;
	}

	public function add(MiddlewareInterface $middleware): self
	{
		if (!$this->isEmpty()) {
			// making middlewares chained to each other
			end($this->layers)->setNext();
		}

		$this->layers[] = $middleware;

		return $this;
	}

	public function dive(Request $request): Response
	{
		if ($this->isEmpty()) {
			throw new \LogicException('layers array is empty - you need to add at least one Middleware of MiddlewareInterface type!');
		}

		// Based on next property of middelwares, if current midleware can't handle the request, then 
		// next one will 'process' it (Request object will be passed to next middleware).
		return $this->layers[0]->process($request);
	}
}