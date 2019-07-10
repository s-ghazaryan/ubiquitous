<?php

namespace App\Services\Api\Json;

use Symfony\Component\HttpFoundation;

class JsonApiRequestGuru
{
	// Based on this values query params will be extracted 
	// page params
	const PAGE_PARAM = 'page';
	const PAGE_SIZE = 'size';
	const PAGE_NUMBER = 'number';

	const INCLUDED_PARAM = 'included';
	const SORT_PARAM = 'sort';
	const FIELDS_PARAM ='fields';

	/**
	 * @var Request
	 */
	private $request; 

	/**
	 * @var array|null
	 */
	private $pageParams = null;

	/**
	 * @var string|null
	 */
	private $includedParams = null;

	/**
	 * @var string|null
	 */
	private $sortParams = null;

	/**
	 * @var array|null
	 */
	private $fieldsParams = null;	

	public function __construct(?HttpFoundation\Request $request = null)
	{
		$this->request = $request;

		if ($request) {
			$this->populateParams($request, self::PAGE_PARAM);
			$this->populateParams($request, self::INCLUDED_PARAM);
			$this->populateParams($request, self::SORT_PARAM);
			$this->populateParams($request, self::FIELDS_PARAM);
		}
	}

	public function populateParams(HttpFoundation\Request $request, string $queryParamKey): void
	{
		$propertyName = $this->generatePropertyName($queryParamKey);
		$this->$propertyName = isset($request->query->all()[$queryParamKey]) ? $request->query->all()[$queryParamKey] : null;
	}

	private function generatePropertyName(string $queryParamKey): string
	{
		return $queryParamKey. 'Params';
	}

	public function getRequest(): HttpFoundation\Request
	{
		return $this->request;
	}

	public function getPageParams(): ?array
	{
		return $this->pageParams;
	}

	public function setPageParams(?array $pageParams): void
	{
		$this->pageParams = $pageParams;
	}

	public function getIncludeParams(): ?string
	{
		return $this->includedParams;
	}

	public function setIncludeParams(?string $includedParams): void
	{
		$this->includedParams = $includedParams;
	}

	public function getSortParams(): ?string
	{
		return $this->sortParams;
	}

	public function setSortParams(?string $sortParams): void
	{
		$this->sortParams = $sortParams;
	}

	public function getFieldsParams(): ?array
	{
		return $this->fieldsParams;
	}

	public function setFieldsParams(?array $fieldsParams): void
	{
		$this->fieldsParams = $fieldsParams;
	}

	public function getPageSize(): ?int
	{
		if (empty($this->pageParams)) {
			return null;
		}

		return isset($this->pageParams[self::PAGE_SIZE]) ? $this->pageParams[self::PAGE_SIZE] : null;
	}

	public function getPageNumber(): ?int
	{
		if (empty($this->pageParams)) {
			return null;
		}

		return isset($this->pageParams[self::PAGE_NUMBER]) ? $this->pageParams[self::PAGE_NUMBER] : null;	
	}

	public function prepareSortParams(): array
	{
		if (!$this->sortParams) {
			return [];
		}

		$sortParams = explode(',', $this->sortParams);
		$preparedSortParamsToReturn = [];

		foreach ($sortParams as $sortParam) {
			$sortParam = trim($sortParam);
			if ($sortParam[0] === '-') {
				$preparedSortParamsToReturn[substr($sortParam, 1)] = false;
			} else {
				$preparedSortParamsToReturn[$sortParam] = true;
			}
		}

		return $preparedSortParamsToReturn;
	}

	public function prepareIncludeParams(): array
	{
		if (!$this->includedParams) {
			return [];
		}

		return explode(',', $this->includedParams);
	}
}
