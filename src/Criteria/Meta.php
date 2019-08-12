<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Criteria;

class Meta
{

	/** @var mixed[] */
	protected $meta = [];

	/**
	 * @param mixed[] $meta
	 */
	public function __construct(array $meta)
	{
		$this->meta = $meta;
	}

	/**
	 * @return mixed[]
	 */
	public function getMeta(): array
	{
		return $this->meta;
	}

}
