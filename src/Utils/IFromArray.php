<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Utils;

interface IFromArray
{

	/**
	 * @param mixed[] $data
	 * @return object
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
	 */
	public static function fromArray(array $data);

}
