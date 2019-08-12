<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Searcher\Unit\Validator;

use PHPUnit\Framework\TestCase;
use Tlapnet\Searcher\Criteria\Condition;
use Tlapnet\Searcher\Criteria\Criteria;
use Tlapnet\Searcher\Exception\Logic\InvalidStateException;
use Tlapnet\Searcher\Template\ConditionTemplate;
use Tlapnet\Searcher\Template\CriteriaTemplate;
use Tlapnet\Searcher\Validator\CriteriaValidator;

final class CriteriaValidatorTest extends TestCase
{

	public function testValidate(): void
	{
		$validator = new CriteriaValidator();

		$nameTemplate = new CriteriaTemplate('name');
		$nameTemplate->setConditions([
			new ConditionTemplate('~', 'string'),
		]);

		$ageTemplate = new CriteriaTemplate('age');
		$ageTemplate->setConditions([
			new ConditionTemplate('~', 'string'),
			new ConditionTemplate('=', 'string'),
			new ConditionTemplate('!=', 'string'),
		]);

		$items = ['name' => $nameTemplate, 'age' => $ageTemplate];

		$criteria = new Criteria(
			[],
			[
				new Condition('name', '~', 'Name'),
				new Condition('age', '=', 'Age'),
			]
		);
		self::assertTrue($validator->validate($items, $criteria));
	}

	public function testBadConditionKey(): void
	{
		$validator = new CriteriaValidator();

		$nameTemplate = new CriteriaTemplate('name');
		$nameTemplate->setConditions([
			new ConditionTemplate('~', 'string'),
		]);

		$ageTemplate = new CriteriaTemplate('age');
		$ageTemplate->setConditions([
			new ConditionTemplate('~', 'string'),
			new ConditionTemplate('=', 'string'),
			new ConditionTemplate('!=', 'string'),
		]);

		$items = ['name' => $nameTemplate, 'age' => $ageTemplate];

		$criteria = new Criteria(
			[],
			[
				new Condition('process', '~', 'Name'),
				new Condition('age', '=', 'Age'),
			]
		);
		$this->expectException(InvalidStateException::class);
		$validator->validate($items, $criteria);
	}

	public function testBadConditionOperator(): void
	{
		$validator = new CriteriaValidator();

		$nameTemplate = new CriteriaTemplate('name');
		$nameTemplate->setConditions([
			new ConditionTemplate('~', 'string'),
		]);

		$ageTemplate = new CriteriaTemplate('age');
		$ageTemplate->setConditions([
			new ConditionTemplate('~', 'string'),
			new ConditionTemplate('=', 'string'),
			new ConditionTemplate('!=', 'string'),
		]);

		$items = ['name' => $nameTemplate, 'age' => $ageTemplate];

		$criteria = new Criteria(
			[],
			[
				new Condition('name', '=', 'Name'),
				new Condition('age', '=', 'Age'),
			]
		);
		$this->expectException(InvalidStateException::class);
		$validator->validate($items, $criteria);
	}

}
