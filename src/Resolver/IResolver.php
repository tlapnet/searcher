<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Resolver;

use Tlapnet\Searcher\Criteria\Criteria;
use Tlapnet\Searcher\Searcher;

interface IResolver
{

	/**
	 * @return mixed
	 */
	public function encode(Searcher $searcher, Criteria $criteria);

	/**
	 * @param mixed $data
	 */
	public function decode(Searcher $searcher, $data): Criteria;

}
