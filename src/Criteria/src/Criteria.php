<?php

declare(strict_types=1);

namespace spaceonfire\Criteria;

use Webmozart\Assert\Assert;
use Webmozart\Expression\Expression;

class Criteria implements CriteriaInterface
{
    /**
     * @var Expression|null
     */
    protected $expression;
    /**
     * @var array<string,int>
     */
    protected $orderBy = [];
    /**
     * @var int|null
     */
    protected $offset;
    /**
     * @var int|null
     */
    protected $limit;
    /**
     * @var mixed[]
     */
    protected $include = [];

    /**
     * @inheritDoc
     */
    public function getWhere(): ?Expression
    {
        return $this->expression;
    }

    /**
     * @inheritDoc
     */
    public function where(?Expression $expression): CriteriaInterface
    {
        $this->expression = $expression;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getOrderBy(): array
    {
        return $this->orderBy;
    }

    /**
     * @inheritDoc
     */
    public function orderBy(array $orderBy): CriteriaInterface
    {
        $invalidMessage = 'Argument $orderBy must be an array where string keys mapped to `SORT_ASC` or `SORT_DESC` constants.';
        Assert::allString(array_keys($orderBy), $invalidMessage);
        Assert::allOneOf($orderBy, [SORT_ASC, SORT_DESC], $invalidMessage);
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getOffset(): int
    {
        return $this->offset ?? 0;
    }

    /**
     * @inheritDoc
     */
    public function offset(?int $offset): CriteriaInterface
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @inheritDoc
     */
    public function limit(?int $limit): CriteriaInterface
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getInclude(): array
    {
        return $this->include;
    }

    /**
     * @inheritDoc
     */
    public function include(array $include): CriteriaInterface
    {
        $this->include = $include;
        return $this;
    }

    /**
     * @inheritDoc
     * The original criteria will not be changed, a new one will be returned instead.
     */
    public function merge(CriteriaInterface $criteria): CriteriaInterface
    {
        $clone = clone $this;

        if (!empty($criteria->getOrderBy())) {
            $clone->orderBy($criteria->getOrderBy());
        }

        if (!empty($criteria->getInclude())) {
            $clone->include($criteria->getInclude());
        }

        if ($criteria->getLimit() !== null) {
            $clone->limit($criteria->getLimit());
            $clone->offset($criteria->getOffset());
        } elseif ($criteria->getOffset() > 0) {
            $clone->offset($criteria->getOffset());
        }

        if ($criteria->getWhere() !== null) {
            $clone->where($criteria->getWhere());
        }

        return $clone;
    }
}
