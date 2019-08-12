<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Resolver;

use Nette\StaticClass;
use Nette\Utils\DateTime;
use Nette\Utils\Json;
use Tlapnet\Searcher\Exception\Logic\InvalidStateException;
use Tlapnet\Searcher\Searcher;
use Tlapnet\Searcher\Type\SearchVariable;

class TypeResolver
{

	use StaticClass;

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	public static function decodeConditionValue(Searcher $searcher, string $property, string $operator, $value)
	{
		foreach ($searcher->getTemplates() as $item) {
			foreach ($item->getConditions() as $conditionTemplate) {
				if ($property === $item->getKey() && $operator === $conditionTemplate->getOperator()) {
					$type = gettype($value);
					$allowedTypes = $conditionTemplate->getType();

					if (in_array($type, $allowedTypes, true)) {
						if (in_array('NULL', $allowedTypes, true) && ($value === '' || (is_string($value) && mb_strtolower($value, 'UTF-8') === 'null'))) {
							return null;
						}

						return $value;
					}

					// Retype string
					if (!in_array('string', $allowedTypes, true) && is_string($value)) {
						// Null
						if (in_array('NULL', $allowedTypes, true) && $value === '') {
							return null;
						}

						// Int
						if (in_array('integer', $allowedTypes, true)) {
							return (int) $value;
						}

						// Bool
						if (in_array('boolean', $allowedTypes, true)) {
							return (bool) $value;
						}

						// DateTime
						if (in_array('datetime', $allowedTypes, true)) {
							return DateTime::from($value);
						}

						if (in_array('searchVariable', $allowedTypes, true)) {
							return SearchVariable::fromArray(Json::decode($value, Json::FORCE_ARRAY));
						}
					}

					// SearchVariable
					if (in_array('searchVariable', $allowedTypes, true)) {
						return SearchVariable::fromArray($value);
					}

					return $value;
				}
			}
		}

		throw new InvalidStateException('ConditionTemplate is not exist');
	}

}
