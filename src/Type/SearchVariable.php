<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Type;

use Nette\Utils\Json;
use Tlapnet\Searcher\Utils\IFromArray;
use Tlapnet\Searcher\Utils\IToArray;

final class SearchVariable implements IToArray, IFromArray
{

	/** @var string */
	private $name;

	/** @var string[] */
	private $keys;

	/** @var mixed */
	private $value;

	/**
	 * @param string[] $keys
	 * @param mixed $value
	 */
	public function __construct(string $name, array $keys, $value)
	{
		$this->name = $name;
		$this->keys = $keys;
		$this->value = $value;
	}

	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return string[]
	 */
	public function getKeys(): array
	{
		return $this->keys;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param mixed[] $data
	 * @return object
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
	 */
	public static function fromArray(array $data)
	{
		return new self($data['name'], $data['keys'], $data['value']);
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return [
			'name' => $this->name,
			'keys' => $this->keys,
			'value' => $this->value,
		];
	}

	public function __toString(): string
	{
		return Json::encode($this->toArray());
	}

}
