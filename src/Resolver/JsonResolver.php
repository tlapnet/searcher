<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Resolver;

use Nette\Utils\Json;
use Tlapnet\Searcher\Criteria\Condition;
use Tlapnet\Searcher\Criteria\Criteria;
use Tlapnet\Searcher\Criteria\Field;
use Tlapnet\Searcher\Criteria\Meta;
use Tlapnet\Searcher\Criteria\Sort;
use Tlapnet\Searcher\Searcher;
use Tlapnet\Searcher\Utils\IToArray;

class JsonResolver implements IResolver
{

	public function encode(Searcher $searcher, Criteria $criteria): string
	{
		return Json::encode([
			'fields' => $this->encodeFields($criteria->getFields()),
			'conditions' => $this->encodeConditions($criteria->getConditions()),
			'sort' => $this->encodeSort($criteria->getSort()),
			'meta' => $this->encodeMeta($criteria->getMeta()),
		]);
	}

	/**
	 * @param Field[] $fields
	 * @return mixed[]
	 */
	public function encodeFields(array $fields): array
	{
		return array_map(function (Field $field): array {
			return [
				'key' => $field->getKey(),
				'width' => $field->getWidth(),
				'align' => $field->getAlign(),
			];
		}, $fields);
	}

	/**
	 * @param Condition[] $conditions
	 * @return mixed[]
	 */
	public function encodeConditions(array $conditions): array
	{
		return array_map(function (Condition $item): array {
			$value = $item->getValue();

			return [
				'property' => $item->getProperty(),
				'operator' => $item->getOperator(),
				'value' => $value instanceof IToArray ? $value->toArray() : $value,
			];
		}, $conditions);
	}

	/**
	 * @param Sort[] $sort
	 * @return mixed[]
	 */
	public function encodeSort(array $sort): array
	{
		return array_map(function (Sort $item): array {
			return [
				'property' => $item->getProperty(),
				'ascending' => $item->isAscending(),
			];
		}, $sort);
	}

	/**
	 * @return mixed[]
	 */
	public function encodeMeta(?Meta $meta): array
	{
		return $meta !== null ? $meta->getMeta() : [];
	}

	/**
	 * @param mixed $data
	 */
	public function decode(Searcher $searcher, $data): Criteria
	{
		$data = Json::decode($data, Json::FORCE_ARRAY);

		$fields = $this->decodeFields($data['fields']);
		$conditions = $this->decodeConditions($searcher, $data['conditions']);
		$sort = $this->decodeSort($data['sort']);
		$meta = $this->decodeMeta($data['meta']);

		return $searcher->build($fields, $conditions, $sort, $meta);
	}

	/**
	 * @param mixed[] $data
	 * @return Field[]
	 */
	public function decodeFields(array $data): array
	{
		return array_map(function (array $item): Field {
			return new Field($item['key'], $item['width'], $item['align']);
		}, $data);
	}

	/**
	 * @param mixed[] $data
	 * @return Condition[]
	 */
	public function decodeConditions(Searcher $searcher, array $data): array
	{
		return array_map(function (array $item) use ($searcher): Condition {
			$property = $item['property'];
			$operator = $item['operator'];
			$value = TypeResolver::decodeConditionValue($searcher, $property, $operator, $item['value']);

			return new Condition($property, $operator, $value);
		}, $data);
	}

	/**
	 * @param mixed[] $data
	 * @return Sort[]
	 */
	public function decodeSort(array $data): array
	{
		return array_map(function (array $item): Sort {
			return new Sort($item['property'], $item['ascending']);
		}, $data);
	}

	/**
	 * @param mixed[] $data
	 */
	public function decodeMeta(array $data): ?Meta
	{
		return !empty($data)
			? new Meta(['type' => $data['type']])
			: null;
	}

}
