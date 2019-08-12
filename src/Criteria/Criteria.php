<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Criteria;

class Criteria
{

	/** @var Field[] */
	private $fields = [];

	/** @var Condition[] */
	private $conditions = [];

	/** @var Sort[] */
	private $sort = [];

	/** @var Meta|NULL */
	private $meta = null;

	/**
	 * @param Field[] $fields
	 * @param Condition[] $conditions
	 * @param Sort[] $sort
	 */
	public function __construct(array $fields = [], array $conditions = [], array $sort = [], ?Meta $meta = null)
	{
		$this->fields = $fields;
		$this->conditions = $conditions;
		$this->sort = $sort;
		$this->meta = $meta;
	}

	/**
	 * @return Field[]
	 */
	public function getFields(): array
	{
		return $this->fields;
	}

	/**
	 * @return Condition[]
	 */
	public function getConditions(): array
	{
		return $this->conditions;
	}

	/**
	 * @return Sort[]
	 */
	public function getSort(): array
	{
		return $this->sort;
	}

	public function getMeta(): ?Meta
	{
		return $this->meta;
	}

}
