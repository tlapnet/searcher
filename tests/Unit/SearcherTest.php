<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Searcher\Unit;

use PHPUnit\Framework\TestCase;
use Tlapnet\Searcher\Searcher;
use Tlapnet\Searcher\Template\ConditionTemplate;
use Tlapnet\Searcher\Template\CriteriaTemplate;
use Tlapnet\Searcher\Template\SortTemplate;

final class SearcherTest extends TestCase
{

	public function testGetAllowedConditions(): void
	{
		$nameTemplate = new CriteriaTemplate('name');
		$nameTemplate->setConditions([
			new ConditionTemplate('~'),
		]);

		$ageTemplate = new CriteriaTemplate('age');
		$ageTemplate->setConditions([
			new ConditionTemplate('~'),
			new ConditionTemplate('='),
			new ConditionTemplate('!='),
		]);

		$taskTemplate = new CriteriaTemplate('task');
		$taskTemplate->setConditions([]);

		$searcher = new Searcher([$nameTemplate, $ageTemplate, $taskTemplate]);
		self::assertSame(['name' => ['~'], 'age' => ['~', '=', '!=']], $searcher->getAllowedConditions());
	}

	public function testGetAllowedSort(): void
	{
		$nameTemplate = new CriteriaTemplate('name');
		$nameTemplate->setSort(new SortTemplate('orderByName'));

		$ageTemplate = new CriteriaTemplate('age');
		$ageTemplate->setSort(new SortTemplate('orderByAge'));

		$taskTemplate = new CriteriaTemplate('task');
		$taskTemplate->setSort(null);

		$searcher = new Searcher([$nameTemplate, $ageTemplate, $taskTemplate]);
		self::assertSame(['name', 'age'], $searcher->getAllowedSort());
	}

}
