<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Searcher\Unit\Utils;

use PHPUnit\Framework\TestCase;
use Tlapnet\Searcher\Criteria\Condition;
use Tlapnet\Searcher\Criteria\Criteria;
use Tlapnet\Searcher\Criteria\Meta;
use Tlapnet\Searcher\Criteria\Sort;
use Tlapnet\Searcher\Utils\CriteriaComparator;

final class CriteriaComparatorTest extends TestCase
{

	public function testCompareMeta(): void
	{
		$folder = new Criteria([], [], [], new Meta(['type' => 'type1']));
		$currentSame = new Criteria([], [], [], new Meta(['type' => 'type1']));
		self::assertTrue(CriteriaComparator::compare($folder, $currentSame));
		$currentNotSame = new Criteria([], [], [], new Meta(['type' => 'type2']));
		self::assertFalse(CriteriaComparator::compare($folder, $currentNotSame));
	}

	public function testCompareConditions(): void
	{
		$folder = new Criteria([], [
			new Condition('process', Condition::LIKE, 'foo'),
			new Condition('step', Condition::NOT_EQ, 'bar'),
		]);

		// Same
		$currentSame = new Criteria([], [
			new Condition('process', Condition::LIKE, 'foo'),
			new Condition('step', Condition::NOT_EQ, 'bar'),
		]);
		self::assertTrue(CriteriaComparator::compare($folder, $currentSame));

		// Changed order
		$currentChangedOrder = new Criteria([], [
			new Condition('step', Condition::NOT_EQ, 'bar'),
			new Condition('process', Condition::LIKE, 'foo'),
		]);
		self::assertTrue(CriteriaComparator::compare($folder, $currentChangedOrder));

		// Changed value
		$currentNotSame = new Criteria([], [
			new Condition('step', Condition::NOT_EQ, 'bar'),
			new Condition('process', Condition::LIKE, 'fooo'),
		]);
		self::assertFalse(CriteriaComparator::compare($folder, $currentNotSame));

		// Changed operator
		$currentNotSame = new Criteria([], [
			new Condition('step', Condition::EQ, 'bar'),
			new Condition('process', Condition::LIKE, 'foo'),
		]);
		self::assertFalse(CriteriaComparator::compare($folder, $currentNotSame));

		// Changed key
		$currentNotSame = new Criteria([], [
			new Condition('task', Condition::NOT_EQ, 'bar'),
			new Condition('process', Condition::LIKE, 'foo'),
		]);
		self::assertFalse(CriteriaComparator::compare($folder, $currentNotSame));

		// Added item
		$currentNotSame = new Criteria([], [
			new Condition('step', Condition::NOT_EQ, 'bar'),
			new Condition('task', Condition::NOT_EQ, 'bar'),
			new Condition('process', Condition::LIKE, 'foo'),
		]);
		self::assertFalse(CriteriaComparator::compare($folder, $currentNotSame));

		// Removed item
		$currentNotSame = new Criteria([], [
			new Condition('step', Condition::NOT_EQ, 'bar'),
		]);
		self::assertFalse(CriteriaComparator::compare($folder, $currentNotSame));
	}

	public function testCompareSort(): void
	{
		$folder = new Criteria([], [], [
			new Sort('process', true),
			new Sort('step', false),
		]);

		// Same
		$currentSame = new Criteria([], [], [
			new Sort('process', true),
			new Sort('step', false),
		]);
		self::assertTrue(CriteriaComparator::compare($folder, $currentSame));

		// Changed order
		$currentChangedOrder = new Criteria([], [], [
			new Sort('step', false),
			new Sort('process', true),
		]);
		self::assertTrue(CriteriaComparator::compare($folder, $currentChangedOrder));

		// Changed ascending
		$currentNotSame = new Criteria([], [], [
			new Sort('process', false),
			new Sort('step', false),
		]);
		self::assertFalse(CriteriaComparator::compare($folder, $currentNotSame));

		// Changed key
		$currentNotSame = new Criteria([], [], [
			new Sort('task', true),
			new Sort('step', false),
		]);
		self::assertFalse(CriteriaComparator::compare($folder, $currentNotSame));

		// Added item
		$currentNotSame = new Criteria([], [], [
			new Sort('process', true),
			new Sort('step', false),
			new Sort('task', false),
		]);
		self::assertFalse(CriteriaComparator::compare($folder, $currentNotSame));

		// Removed item
		$currentNotSame = new Criteria([], [], [
			new Sort('process', true),
		]);
		self::assertFalse(CriteriaComparator::compare($folder, $currentNotSame));

		// Folder empty - same
		$folderEmpty = new Criteria([], [], []);
		$currentSame = new Criteria([], [], []);
		self::assertTrue(CriteriaComparator::compare($folderEmpty, $currentSame));

		// Folder empty - not same
		$folderEmpty = new Criteria([], [], []);
		self::assertTrue(CriteriaComparator::compare($folderEmpty, $currentNotSame));
	}

}
