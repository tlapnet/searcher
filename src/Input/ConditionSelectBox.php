<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Input;

use Nette\Forms\Controls\SelectBox;
use Tlapnet\Searcher\Criteria\Condition;

class ConditionSelectBox extends SelectBox
{

	/** @var string */
	private $property;

	/** @var string */
	private $operator;

	/**
	 * @param mixed[]|null $items
	 */
	public function __construct(string $property, string $operator, ?array $items = null)
	{
		$this->property = $property;
		$this->operator = $operator;
		parent::__construct(null, $items);
	}

	/**
	 * @param mixed $value
	 * @return static
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
	 */
	public function setValue($value)
	{
		if ($value instanceof Condition) {
			return parent::setValue($value->getValue() !== '' ? $value->getValue() : null);
		}

		return parent::setValue($value);
	}

	public function getValue(): ?Condition
	{
		$value = parent::getValue();

		return $value
			? new Condition($this->property, $this->operator, (string) $value)
			: null;
	}

}
