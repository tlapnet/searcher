<?php declare(strict_types = 1);

namespace Tests\Tlapnet\Searcher\Unit\Resolver;

use Mockery;
use Nette\Utils\Json;
use PHPUnit\Framework\TestCase;
use Tlapnet\Searcher\Criteria\Condition;
use Tlapnet\Searcher\Criteria\Criteria;
use Tlapnet\Searcher\Criteria\Field;
use Tlapnet\Searcher\Criteria\Sort;
use Tlapnet\Searcher\Exception\Logic\InvalidStateException;
use Tlapnet\Searcher\Resolver\JsonResolver;
use Tlapnet\Searcher\Searcher;
use Tlapnet\Searcher\Template\ConditionTemplate;
use Tlapnet\Searcher\Template\CriteriaTemplate;
use Tlapnet\Searcher\Template\SortTemplate;
use Tlapnet\Searcher\Type\SearchVariable;

final class JsonResolverTest extends TestCase
{

	public function testDecode(): void
	{
		$json = file_get_contents(__DIR__ . '/criteria.json');

		if ($json === false) {
			throw new InvalidStateException('Missing file criteria.json');
		}

		$id = new CriteriaTemplate('id');
		$id->setConditions([new ConditionTemplate('=', 'integer')]);
		$id->setSort(new SortTemplate('orderById'));

		$title = new CriteriaTemplate('title');
		$title->setConditions([new ConditionTemplate('~', 'string')]);
		$title->setSort(new SortTemplate('orderByTitle'));

		$variable = new CriteriaTemplate('complexVariable');
		$variable->setConditions([new ConditionTemplate('=', 'searchVariable')]);

		$simpleVariable = new CriteriaTemplate('simpleVariable');
		$simpleVariable->setConditions([new ConditionTemplate('=', 'string|NULL')]);

		$searcher = new Searcher([$id, $title, $variable, $simpleVariable]);

		$expectedCriteria = new Criteria(
			[
				new Field('id', 120, 'right'),
				new Field('title', null, null),
			],
			[
				new Condition('id', '=', 1),
				new Condition('title', '~', 'foo'),
				new Condition('complexVariable', '=', new SearchVariable('antenna', ['inStock'], true)),
				new Condition('simpleVariable', '=', null),
			],
			[
				new Sort('id', true),
				new Sort('title', false),
			],
			null
		);

		$jsonResolver = new JsonResolver();
		$criteria = $jsonResolver->decode($searcher, $json);
		self::assertEquals($expectedCriteria, $criteria);
	}

	public function testEncode(): void
	{
		$json = file_get_contents(__DIR__ . '/criteria.json');

		if ($json === false) {
			throw new InvalidStateException('Missing file criteria.json');
		}

		$json = Json::encode(Json::decode($json));

		$criteria = new Criteria(
			[
				new Field('id', 120, 'right'),
				new Field('title', null, null),
			],
			[
				new Condition('id', '=', 1),
				new Condition('title', '~', 'foo'),
				new Condition('complexVariable', '=', new SearchVariable('antenna', ['inStock'], true)),
				new Condition('simpleVariable', '=', null),
			],
			[
				new Sort('id', true),
				new Sort('title', false),
			],
			null
		);

		$jsonResolver = new JsonResolver();

		$resolvedJson = $jsonResolver->encode(Mockery::mock(Searcher::class), $criteria);
		self::assertSame($json, $resolvedJson);
	}

}
