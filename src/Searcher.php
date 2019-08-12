<?php declare(strict_types = 1);

namespace Tlapnet\Searcher;

use Tlapnet\Searcher\Builder\CriteriaBuilder;
use Tlapnet\Searcher\Criteria\Condition;
use Tlapnet\Searcher\Criteria\Criteria;
use Tlapnet\Searcher\Criteria\Field;
use Tlapnet\Searcher\Criteria\Meta;
use Tlapnet\Searcher\Criteria\Sort;
use Tlapnet\Searcher\Exception\Logic\InvalidStateException;
use Tlapnet\Searcher\Resolver\IResolver;
use Tlapnet\Searcher\Resolver\JsonResolver;
use Tlapnet\Searcher\Template\CriteriaTemplate;
use Tlapnet\Searcher\Validator\CriteriaValidator;

class Searcher
{

	public const RESOLVER_TYPE_JSON = 'json';

	/** @var CriteriaTemplate[] */
	protected $templates = [];

	/** @var CriteriaBuilder */
	protected $builder;

	/** @var CriteriaValidator */
	protected $validator;

	/** @var IResolver[] */
	protected $resolvers = [];

	/**
	 * @param CriteriaTemplate[] $templates
	 */
	public function __construct(array $templates)
	{
		$this->templates = $templates;
		$this->builder = new CriteriaBuilder();
		$this->validator = new CriteriaValidator();
		$this->resolvers = [
			self::RESOLVER_TYPE_JSON => new JsonResolver(),
		];
	}

	public function getBuilder(): CriteriaBuilder
	{
		return $this->builder;
	}

	public function getValidator(): CriteriaValidator
	{
		return $this->validator;
	}

	/**
	 * @param Field[]|null $fields
	 * @param Condition[]|null $conditions
	 * @param Sort[]|null $sort
	 */
	public function build(
		?array $fields = null,
		?array $conditions = null,
		?array $sort = null,
		?Meta $meta = null
	): Criteria
	{
		$criteria = $this->builder->build($fields, $conditions, $sort, $meta);
		$this->validate($criteria);

		return $criteria;
	}

	public function validate(Criteria $criteria): void
	{
		$this->validator->validate($this->templates, $criteria);
	}

	/**
	 * @return mixed
	 */
	public function encode(Criteria $criteria, string $type)
	{
		if (isset($this->resolvers[$type])) {
			return $this->resolvers[$type]->encode($this, $criteria);
		}

		throw new InvalidStateException('Unknown resolver type "' . $type . '"');
	}

	/**
	 * @param mixed $data
	 */
	public function decode($data, string $type): Criteria
	{
		if (isset($this->resolvers[$type])) {
			return $this->resolvers[$type]->decode($this, $data);
		}

		throw new InvalidStateException('Unknown resolver type "' . $type . '"');
	}

	/**
	 * @return CriteriaTemplate[]
	 */
	public function getTemplates(): array
	{
		return $this->templates;
	}

	/**
	 * @return mixed[]
	 */
	public function getAllowedConditions(): array
	{
		$conditions = [];

		foreach ($this->templates as $item) {
			$allowedOperators = $item->getAllowedOperators();

			if (count($allowedOperators) > 0) {
				$conditions[$item->getKey()] = $allowedOperators;
			}
		}

		return $conditions;
	}

	/**
	 * @return string[]
	 */
	public function getAllowedSort(): array
	{
		$sort = [];

		foreach ($this->templates as $item) {
			if ($item->getSort() !== null) {
				$sort[] = $item->getKey();
			}
		}

		return $sort;
	}

	/**
	 * @return string[]
	 */
	public function getAllowedView(): array
	{
		$view = [];

		foreach ($this->templates as $item) {
			if ($item->getView() !== null) {
				$view[] = $item->getKey();
			}
		}

		return $view;
	}

	/**
	 * @return string[]
	 */
	public function getProperties(): array
	{
		$templates = $this->getTemplates();
		$properties = [];

		foreach ($templates as $template) {
			$properties[$template->getKey()] = $template->getLabel();
		}

		return $properties;
	}

	public function isOperatorAllowed(string $templateKey, string $operator): bool
	{
		$templates = $this->getTemplates();

		foreach ($templates as $template) {
			if ($template->getKey() === $templateKey && in_array($operator, $template->getAllowedOperators(), true)) {
				return true;
			}
		}

		return false;
	}

	public function getPropertyNameForKey(string $templateKey): string
	{
		$templates = $this->getTemplates();

		foreach ($templates as $template) {
			if ($template->getKey() === $templateKey) {
				return $template->getLabel();
			}
		}

		return $templateKey;
	}

}
