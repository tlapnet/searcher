<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Utils;

use Nette\StaticClass;
use Tlapnet\Searcher\Criteria\Condition;
use Tlapnet\Searcher\Criteria\Criteria;
use Tlapnet\Searcher\Criteria\Field;
use Tlapnet\Searcher\Criteria\Meta;
use Tlapnet\Searcher\Criteria\Sort;

class CriteriaComparator
{

	use StaticClass;

	public static function compare(Criteria $folder, Criteria $current): bool
	{
		$meta = $folder->getMeta();
		$currentMeta = $current->getMeta();

		return self::compareFields($folder->getFields(), $current->getFields()) &&
			self::compareConditions($folder->getConditions(), $current->getConditions()) &&
			self::compareSort($folder->getSort(), $current->getSort()) &&
			self::compareMeta($meta, $currentMeta);
	}

	/**
	 * @param Field[] $folder
	 * @param Field[] $current
	 */
	public static function compareFields(array $folder, array $current): bool
	{
		return true;
	}

	/**
	 * @param Condition[] $folder
	 * @param Condition[] $current
	 */
	public static function compareConditions(array $folder, array $current): bool
	{
		if (count($folder) !== count($current)) {
			return false;
		}

		$folderData = [];

		foreach ($folder as $condition) {
			$value = $condition->getValue();
			$folderData[$condition->getProperty()] = [
				'property' => $condition->getProperty(),
				'operator' => $condition->getOperator(),
				'value' => $value instanceof IToArray ? $value->toArray() : (string) $value,
			];
		}

		ksort($folderData);

		$currentData = [];

		foreach ($current as $condition) {
			$value = $condition->getValue();
			$currentData[$condition->getProperty()] = [
				'property' => $condition->getProperty(),
				'operator' => $condition->getOperator(),
				'value' => $value instanceof IToArray ? $value->toArray() : (string) $value,
			];
		}

		ksort($currentData);

		return $folderData === $currentData;
	}

	/**
	 * @param Sort[] $folder
	 * @param Sort[] $current
	 */
	public static function compareSort(array $folder, array $current): bool
	{
		if (count($folder) === 0) {
			return true;
		}

		if (count($folder) !== count($current)) {
			return false;
		}

		// TODO multiple sorting - depends on order of sort?

		$folderData = [];

		foreach ($folder as $sort) {
			$folderData[$sort->getProperty()] = $sort->isAscending();
		}

		ksort($folderData);

		$currentData = [];

		foreach ($current as $sort) {
			$currentData[$sort->getProperty()] = $sort->isAscending();
		}

		ksort($currentData);

		return $folderData === $currentData;
	}

	public static function compareMeta(?Meta $folder, ?Meta $current): bool
	{
		$folder = $folder !== null
			? $folder->getMeta()
			: null;
		$current = $current !== null
			? $current->getMeta()
			: null;

		return $folder === $current;
	}

}
