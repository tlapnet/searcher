<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Template;

class SortTemplate
{

	/** @var string */
	private $query;

	public function __construct(string $query)
	{
		$this->query = $query;
	}

	public function getQuery(): string
	{
		return $this->query;
	}

}
