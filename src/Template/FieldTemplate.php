<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Template;

class FieldTemplate
{

	/** @var bool */
	private $show;

	/** @var string */
	private $query;

	public function __construct(bool $show, string $query)
	{
		$this->show = $show;
		$this->query = $query;
	}

	public function isShow(): bool
	{
		return $this->show;
	}

	public function getQuery(): string
	{
		return $this->query;
	}

}
