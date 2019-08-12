<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Criteria;

class Field
{

	/** @var string */
	private $key;

	/** @var int|null */
	private $width;

	/** @var string|null */
	private $align;

	public function __construct(string $key, ?int $width, ?string $align)
	{
		$this->key = $key;
		$this->width = $width;
		$this->align = $align;
	}

	public function getKey(): string
	{
		return $this->key;
	}

	public function getWidth(): ?int
	{
		return $this->width;
	}

	public function getAlign(): ?string
	{
		return $this->align;
	}

}
