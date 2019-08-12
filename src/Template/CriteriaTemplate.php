<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Template;

class CriteriaTemplate
{

	/** @var string */
	private $key;

	/** @var string */
	private $label;

	/** @var string|null */
	private $description;

	/** @var ConditionTemplate[] */
	private $conditions = [];

	/** @var SortTemplate|null */
	private $sort;

	/** @var FieldTemplate|null */
	private $view;

	public function __construct(string $key, ?string $label = NULL)
	{
		$this->key = $key;
		$this->label = $label ?? $key;
	}

	/**
	 * @return ConditionTemplate[]
	 */
	public function getConditions(): array
	{
		return $this->conditions;
	}

	/**
	 * @param ConditionTemplate[] $conditions
	 */
	public function setConditions(array $conditions): self
	{
		$this->conditions = $conditions;
		return $this;
	}

	public function getSort(): ?SortTemplate
	{
		return $this->sort;
	}

	public function setSort(?SortTemplate $sort): self
	{
		$this->sort = $sort;
		return $this;
	}

	public function getView(): ?FieldTemplate
	{
		return $this->view;
	}

	public function setView(?FieldTemplate $view): self
	{
		$this->view = $view;
		return $this;
	}

	public function getKey(): string
	{
		return $this->key;
	}

	public function getLabel(): string
	{
		return $this->label;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): self
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string[]
	 */
	public function getAllowedOperators(): array
	{
		$operators = [];
		foreach ($this->conditions as $condition) {
			$operators[] = $condition->getOperator();
		}
		return $operators;
	}

}
