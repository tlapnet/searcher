<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Criteria;

class Sort
{

	/** @var string */
	private $property;

	/** @var bool */
	private $ascending;

	public function __construct(string $property, bool $ascending)
	{
		$this->property = $property;
		$this->ascending = $ascending;
	}

	public function isAscending(): bool
	{
		return $this->ascending;
	}

	public function getProperty(): string
	{
		return $this->property;
	}

}
