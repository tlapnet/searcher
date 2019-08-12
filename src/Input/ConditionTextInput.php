<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Input;

use Nette\Forms\Controls\TextInput;
use Tlapnet\Searcher\Criteria\Condition;

class ConditionTextInput extends TextInput
{

	/** @var string */
	private $property;

	/** @var string */
	private $operator;

	public function __construct(string $property, string $operator, ?int $maxLength = null)
	{
		$this->property = $property;
		$this->operator = $operator;
		parent::__construct(null, $maxLength);
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
			return parent::setValue($value->getValue());
		}

		return parent::setValue($value);
	}

	public function getValue(): ?Condition
	{
		$value = parent::getValue();

		return $value !== '' && $value !== null
			? new Condition($this->property, $this->operator, (string) $value)
			: null;
	}

}
