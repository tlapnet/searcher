<?php declare(strict_types = 1);

namespace Tlapnet\Searcher\Template;

class ConditionTemplate
{

	/** @var string */
	private $operator;

	/** @var callable|null */
	private $queryCallback;

	/** @var string[] */
	private $type = [];

	/** @var bool */
	private $whispering = FALSE;

	/** @var int */
	private $whisperingOn = 0;

	/** @var string|null */
	private $whisperingType;

	/** @var string[] */
	private $items = [];

	public function __construct(string $operator, ?string $type = NULL)
	{
		$this->operator = $operator;
		$this->type = $type !== NULL ? explode('|', $type) : [];
	}

	public function getOperator(): string
	{
		return $this->operator;
	}

	public function getQueryCallback(): ?callable
	{
		return $this->queryCallback;
	}

	public function setQueryCallback(?callable $queryCallback): self
	{
		$this->queryCallback = $queryCallback;
		return $this;
	}

	/**
	 * @return string[]
	 */
	public function getType(): array
	{
		return $this->type;
	}

	public function isWhispering(): bool
	{
		return $this->whispering;
	}

	public function setWhispering(bool $whispering): self
	{
		$this->whispering = $whispering;
		return $this;
	}

	public function getWhisperingOn(): int
	{
		return $this->whisperingOn;
	}

	public function setWhisperingOn(int $whisperingOn): self
	{
		$this->whisperingOn = $whisperingOn;
		return $this;
	}

	public function getWhisperingType(): ?string
	{
		return $this->whisperingType;
	}

	public function setWhisperingType(?string $whisperingType): self
	{
		$this->whisperingType = $whisperingType;
		return $this;
	}

	/**
	 * @return string[]
	 */
	public function getItems(): array
	{
		return $this->items;
	}

	/**
	 * @param string[] $items
	 */
	public function setItems(array $items): self
	{
		$this->items = $items;
		return $this;
	}

}
