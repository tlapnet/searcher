<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Searcher\Unit\Criteria;

use PHPUnit\Framework\TestCase;
use Tlapnet\Searcher\Criteria\Condition;
use Tlapnet\Searcher\Exception\Logic\InvalidArgumentException;

final class CriteriaTest extends TestCase
{

	public function testOperator(): void
	{
		try {
			new Condition('name', '?=', 'Doe');
			self::fail('Should throw invalid exception error');
		} catch (InvalidArgumentException $ex) {
			self::assertEquals("'?=' is not a valid operator.", $ex->getMessage());
		}
	}

}
