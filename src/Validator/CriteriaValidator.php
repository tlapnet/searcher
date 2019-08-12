<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Validator;

use DateTimeInterface;
use Tlapnet\Searcher\Criteria\Criteria;
use Tlapnet\Searcher\Exception\Logic\InvalidStateException;
use Tlapnet\Searcher\Template\CriteriaTemplate;
use Tlapnet\Searcher\Type\SearchVariable;

class CriteriaValidator
{

	/**
	 * @param CriteriaTemplate[] $items
	 */
	public function validate(array $items, Criteria $criteria): bool
	{
		$allowedSort = [];
		$allowedConditions = [];

		foreach ($items as $item) {
			$allowedOperators = $item->getAllowedOperators();

			if (count($allowedOperators) > 0) {
				$allowedConditions[$item->getKey()] = $allowedOperators;
			}

			if ($item->getSort() !== null) {
				$allowedSort[] = $item->getKey();
			}
		}

		$allowedProperties = array_keys($allowedConditions);

		foreach ($criteria->getConditions() as $condition) {
			if (!in_array($condition->getProperty(), $allowedProperties, true)) {
				throw new InvalidStateException('Unknown condition ' . $condition->getProperty());
			}

			$allowedOperators = $allowedConditions[$condition->getProperty()];

			if (!in_array($condition->getOperator(), $allowedOperators, true)) {
				throw new InvalidStateException('Unknown condition ' . $condition->getProperty() . ' operator ' . $condition->getOperator());
			}

			$value = $condition->getValue();

			foreach ($items as $item) {
				foreach ($item->getConditions() as $conditionTemplate) {
					if ($condition->getProperty() === $item->getKey() && $condition->getOperator() === $conditionTemplate->getOperator()) {
						$type = self::getType($value);

						if (!in_array($type, $conditionTemplate->getType(), true)) {
							throw new InvalidStateException(sprintf(
								'Condition "%s" value %s type "%s" is out of allowed types [%s]',
								$condition->getProperty(),
								is_scalar($value) ? '"' . $value . '"' : null,
								$type,
								implode(', ', $conditionTemplate->getType())
							));
						}
					}
				}
			}
		}

		foreach ($criteria->getSort() as $sort) {
			$key = array_search($sort->getProperty(), $allowedSort, true);

			if ($key === false) {
				throw new InvalidStateException('Unknown sort ' . $sort->getProperty());
			}

			unset($allowedSort[$key]);
		}

		return true;
	}

	/**
	 * @param mixed $value
	 */
	public static function getType($value): string
	{
		$type = gettype($value);

		if ($type === 'object' && $value instanceof DateTimeInterface) {
			return 'datetime';
		}

		if ($type === 'object' && $value instanceof SearchVariable) {
			return 'searchVariable';
		}

		return $type;
	}

}
