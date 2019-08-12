<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Criteria;

use Tlapnet\Searcher\Exception\Logic\InvalidArgumentException;

class Condition
{

	public const EQ = '=';
	public const NOT_EQ = '!=';
	public const GT = '>';
	public const LT = '<';
	public const LIKE = '~';
	public const OPERATORS = [self::EQ, self::NOT_EQ, self::GT, self::LT, self::LIKE];

	/** @var string */
	private $property;

	/** @var string */
	private $operator;

	/** @var mixed */
	private $value;

	/**
	 * @param mixed $value
	 */
	public function __construct(string $property, string $operator, $value)
	{
		if (!in_array($operator, self::OPERATORS, true)) {
			throw new InvalidArgumentException(sprintf('\'%s\' is not a valid operator.', $operator));
		}

		$this->property = $property;
		$this->operator = $operator;
		$this->value = $value;
	}

	public function getProperty(): string
	{
		return $this->property;
	}

	public function getOperator(): string
	{
		return $this->operator;
	}

	/**
	 * @return mixed[]|mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

}
