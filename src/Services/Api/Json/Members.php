<?php

namespace App\Services\Api\Json;

class Members
{
	const ID = 'id';
	const TYPE = 'type';
	const DATA = 'data';
	const ATTRIBUTES = 'attributes';
	const RELATIONSHIPS = 'relationships';
	const LINKS = 'links';
	const INCLUDED = 'included';
	const META = 'meta';
	// self is key word in php
	const SELF_MEMBER = 'self';
	const RELATED = 'related';
	// Generally is meant for pagination
	const FIRST = 'first';
	const PREV = 'prev';
	const NEXT = 'next';
	const LAST = 'last';
}