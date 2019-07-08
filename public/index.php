<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Symfony\Component\HttpFoundation\Request;
use App\Middlewares\LayeredMiddlewaresHub;
use App\Middlewares\RoutingMiddleware;

$layeredMiddlewaresHub = new LayeredMiddlewaresHub();
// Add layers (Middlewares of MiddlewareInterface type) to LayeredMiddlewares!
$layeredMiddlewaresHub->add(new RoutingMiddleware());

// request processing by layed middlewares
$request = Request::createFromGlobals();
$response = $layeredMiddlewaresHub->dive($request);
$response->send();