<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Builder;

use Tlapnet\Searcher\Criteria\Condition;
use Tlapnet\Searcher\Criteria\Criteria;
use Tlapnet\Searcher\Criteria\Field;
use Tlapnet\Searcher\Criteria\Meta;
use Tlapnet\Searcher\Criteria\Sort;

class CriteriaBuilder
{

	/**
	 * @param Field[]|null $fields
	 * @param Condition[]|null $conditions
	 * @param Sort[]|null $sort
	 */
	public function build(
		?array $fields = null,
		?array $conditions = null,
		?array $sort = null,
		?Meta $meta = null
	): Criteria
	{
		$fields = $fields ?? $this->getDefaultFields();
		$conditions = $conditions ?? $this->getDefaultConditions();
		$sort = $sort ?? $this->getDefaultSort();
		$meta = $meta ?? $this->getDefaultMeta();

		return new Criteria($fields, $conditions, $sort, $meta);
	}

	/**
	 * @return Field[]
	 */
	public function getDefaultFields(): array
	{
		return [];
	}

	/**
	 * @return Condition[]
	 */
	public function getDefaultConditions(): array
	{
		return [];
	}

	/**
	 * @return Sort[]
	 */
	public function getDefaultSort(): array
	{
		return [];
	}

	public function getDefaultMeta(): ?Meta
	{
		return null;
	}

}
