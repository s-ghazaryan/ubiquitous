<?php

namespace App\Services\Api\Json;

use Symfony\Component\HttpFoundation\ParameterBag;

class Resource
{
	/**
	 * Based on this constants data will be estracted from resource object.
	 * 
	 * const is defined based on JSON:API (v1.0) specification.
	 */
	const ID = 'id';
	const TYPE = 'type';
	const DATA = 'data';
	const ATTRIBUTES = 'attributes';
	const RELATIONSHIPS = 'relationships';
	const LINKS = 'links';
	const INCLUDED = 'included';
	const META = 'meta';

	/**
	 * @var bool
	 */
	private $dataKeyIsAbsent;

	/**
	 * @var int|null
	 */
	private $id = null;

	/**
	 * @var string|null
	 */
	private $type = null;

	/**
	 * If data member stores array, then every object of
	 * that array will be treated as separate resource.
	 *
	 * @var Resource[]
	 */
	private $resources;

	/**
	 * @var ParameterBag
	 */
	private $attributes;

	/**
	 * @var ParameterBag
	 */
	private $relationships;

	/**
	 * Root level member
	 *
	 * @var ParameterBag $primaryDataLinks;
	 */
	private $primaryDataLinks;

	/**
	 * @var ParameterBag
	 */
	private $links;

	/**
	 * @var Resource[]
	 */
	private $included = [];

	/**
	 * Root level member
	 *
	 * @var ParameterBag
	 */
	private $meta;

	/**
	 * Reversing this object to array, which obays json:api (v1.0) specification.
	 *
	 * @var array
	 */
	private $resourceInJsonApiFormat = [];

	/**
	 * @param array $resourceData this parameter will store all required information
	 * 							  to extract for properties!
	 * 							  $resourceData is considered to be as single resource object.
	 * @param bool $dataKeyIsAbsent based on this param class will deside whether
	 * 								to take into consideration data member/key or not.
	 */
	function __construct(array $resourceData = [], bool $dataKeyIsAbsent = false)
	{
		$this->dataKeyIsAbsent = $dataKeyIsAbsent;

		if (!empty($resourceData)) {
 			$dataMember = $this->isData($resourceData);
			if ($dataMember) {
				foreach($dataMember as $resource) {
					$this->resources[] = new self($resource, true);
				}

				$this->populateRootLevelMembers($resourceData);	
			} else {
				$this->populateProperties($resourceData, $this->dataKeyIsAbsent);	
			}
		} else {
			$this->attributes = new ParameterBag();
			$this->relationships = new ParameterBag();
			$this->primaryDataLinks = new ParameterBag();
			$this->links = new ParameterBag();
			$this->meta = new ParameterBag();
		}
	}

	public function populateProperties(array $resourceData, bool $dataKeyIsAbsent = false): void
	{
		// extraction of data fields
		$this->setId($this->extractDataField(self::ID, $resourceData, null));
		$this->setType($this->extractDataField(self::TYPE, $resourceData, null));
		$this->setAttributes($this->extractDataField(self::ATTRIBUTES, $resourceData, []));
		$this->setRelationships($this->extractDataField(self::RELATIONSHIPS, $resourceData, []));

		// This fealds need to be extracted explicitly!
		$this->setLinks($this->extractLinks($resourceData, $dataKeyIsAbsent));

		// extraction of root level fields (e.g. included, links, meta, etc.)
		$this->populateRootLevelMembers($resourceData);
	}

	/**
	 * Populates some properties based on root members.
	 *
	 * @param array $resourceData
	 *
	 * @return void
	 */
	private function populateRootLevelMembers(array $resourceData): void
	{
		$this->setPrimaryDataLinks($this->extractRootLevelField(self::LINKS, $resourceData, []));
		$this->setIncluded($this->extractRootLevelField(self::INCLUDED, $resourceData, []));
		$this->setMeta($this->extractRootLevelField(self::META, $resourceData, []));
	}

	/**
	 * Extracts data filed from given $resourceData array
	 * 
	 * Recommendation: don't extract links of data member with this method,
	 * because root level links may be fetched instead.
	 *
	 * @param string $fieldName
	 * @param array $resourceData
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function extractDataField(string $fieldName, array $resourceData, $default)
	{
		if (self::inConstants($fieldName)) {
			$data = $this->isData($resourceData);
			if ($data) {
				return isset($data[$fieldName]) ? $data[$fieldName] : $default;
			}

			return isset($resourceData[$fieldName]) ? $resourceData[$fieldName] : $default;		
		}
	}

	/**
	 * Extracts root level filed from given $resourceData array
	 *
	 * @param string $fieldName
	 * @param array $resourceData
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function extractRootLevelField(string $fieldName, array $resourceData, $default)
	{
		if (self::inConstants($fieldName) && $this->isData($resourceData)) {
			return isset($resourceData[$fieldName]) ? $resourceData[$fieldName] : $default;	
		}

		return $default;
	}

	/**
	 * Extracts links from passed array
	 *
	 * @param array $rosourceData
	 * @param bool $dataKeyIsAbsent based on this param functions will deside whether
	 * 								to take into consideration data key or not.
	 *
	 * @return array
	 */
	public function extractLinks(array $resourceData, bool $dataKeyIsAbsent = false): array
	{
		$data = $this->isData($resourceData);
		if ($data) {
			return isset($data[self::LINKS]) ? $data[self::LINKS] : [];
		} elseif ($dataKeyIsAbsent) {
			return isset($resourceData[self::LINKS]) ? $resourceData[self::LINKS] : [];
		}

		return [];
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(?int $id): void
	{
		$this->id = $id;
	}

	public function getType(): ?string
	{
		return $this->type;
	}

	public function setType(?string $type): void
	{
		$this->type = $type;
	}

	public function getResources(): array
	{
		return $this->resources;
	}

	public function setResources(array $resources): void
	{
		if (!empty($resources)) {
			foreach($resources as $resource) {
				if (!($resource instanceof Resource)) {
					throw new \InvalidArgumentException('given array isn\'t of type ' .self::class. "!");
				}				
			}
		}

		$this->resources = $resources;
	}

	public function addResource(self $resource): void
	{
		if (!($resource instanceof Resource)) {
			throw new \InvalidArgumentException('given array isn\'t of type ' .self::class. "!");
		}

		$this->resources[] = $resource;
	}

	public function getAttributes(): ParameterBag
	{
		return $this->attributes;
	}

	public function setAttributes(array $attributes): void
	{
		$this->attributes = new ParameterBag($attributes);
	}

	public function getRelationships(): ParameterBag
	{
		return $this->relationships;
	}

	public function setRelationships(array $relationships): void
	{
		$this->relationships = new ParameterBag($relationships);
	}

	public function getLinks(): ParameterBag
	{
		return $this->links;
	}

	public function setLinks(array $links): void
	{
		$this->links = new ParameterBag($links);
	}

	public function getPrimaryDataLinks(): ParameterBag
	{
		return $this->primaryDataLinks;
	}

	public function setPrimaryDataLinks(array $primaryDataLinks): void
	{
		$this->primaryDataLinks = new ParameterBag($primaryDataLinks);
	}

	public function getIncluded(): array
	{
		return $this->included;
	}

	public function setIncluded(array $included): void
	{
		foreach ($included as $includedObject) {
			$this->included[] = new self($includedObject, true);
		}
	}

	public function addToIncluded(self $resource): void
	{
		$this->included[] = $resource;
	}

	public function getMeta(): ParameterBag
	{
		return $this->meta;
	}

	public function setMeta(array $meta): void
	{
		$this->meta = new ParameterBag($meta);
	}

	public function isData(array $resourceData): ?array
	{
		if (isset($resourceData[self::DATA])) {
			return $resourceData[self::DATA];
		}

		return null;
	}

	/**
	 * 'getResources' method will return true, if data root member
	 * is storing array.  
	 *
	 * @return bool
	 */
	public function isSingleObject(): bool
	{
		if (count($this->getResources())) {
			return false;
		}

		return true;
	}

	public static function getConstants(): array
	{
		$reflectionObject = new \ReflectionClass(self::class);

		return $reflectionObject->getConstants();
	}

	/**
	 * Checks whether given field is in this class constatns
	 *
	 * @return bool
	 * @throw InvalidArgumentException 
	 */
	public static function inConstants(string $fieldName): bool
	{
		if (!in_array($fieldName, self::getConstants())) {
			throw new \InvalidArgumentException($fieldName. ' Is not defined in constant list of ' .self::class. ' class!');
		}

		return true;
	}

	/**
	 * Prepares resource in json:api (v1.0) format
	 *
	 * This method uses 'semi' template pattern
	 *
	 * @return array
	 */
	public function assemble(): array
	{
		$this->addPrimmaryDataLink();
		$this->addData();
		$this->addIncluded();
		$this->addMeta();

		return $this->resourceInJsonApiFormat;
	}

	private function addPrimmaryDataLink(): void
	{
		$this->resourceInJsonApiFormat[self::LINKS] = $this->primaryDataLinks->all();
	}

	private function addData(): void
	{
		if (empty($this->resources)) {
			$this->resourceInJsonApiFormat[self::DATA][self::ID] = $this->id;
			$this->resourceInJsonApiFormat[self::DATA][self::TYPE] = $this->type;
			$this->resourceInJsonApiFormat[self::DATA][self::ATTRIBUTES] = $this->attributes->all();
			$this->resourceInJsonApiFormat[self::DATA][self::RELATIONSHIPS] = $this->relationships->all();
			$this->resourceInJsonApiFormat[self::DATA][self::LINKS] = $this->links->all();
		} else {
			$this->addMultipleResources($this->resources, self::DATA);
		}
	}

	private function addIncluded(): void
	{
		$this->addMultipleResources($this->included, self::INCLUDED);
	}

	private function addMeta(): void
	{
		$this->resourceInJsonApiFormat[self::META] = $this->meta->all();
	}

	private function addMultipleResources(array $resources, string $memeberToAddTo): void
	{
		foreach ($resources as $index => $resource) {
			$this->resourceInJsonApiFormat[$memeberToAddTo][$index][self::ID] = $resource->id;
			$this->resourceInJsonApiFormat[$memeberToAddTo][$index][self::TYPE] = $resource->type;
			$this->resourceInJsonApiFormat[$memeberToAddTo][$index][self::ATTRIBUTES] = $resource->attributes->all();
			$this->resourceInJsonApiFormat[$memeberToAddTo][$index][self::RELATIONSHIPS] = $resource->relationships->all();
			$this->resourceInJsonApiFormat[$memeberToAddTo][$index][self::LINKS] = $resource->links->all();
		}
	}
}
