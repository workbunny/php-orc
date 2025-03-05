<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc;

use PyObject;
use Workbunny\PhpOrc\Enums\PredicatesOperator;

class Predicate
{

    /**
     * @var PyObject|null
     */
    protected PyObject|null $result = null;

    final public function __construct(int|PredicatesOperator $operator, string $c)
    {
    }

    public function __invoke(): ?PyObject
    {
        return $this->result;
    }

    public function not(Predicate $left, Predicate $right): static
    {
        $this->result = \PyCore::import('pyorc.predicates')->Predicate(0, $left(), $right());
        return $this;
    }

    public function or(Predicate $left, Predicate $right): static
    {
        $this->result = \PyCore::import('pyorc.predicates')->Predicate(1, $left(), $right());
        return $this;
    }

    public function and(PredicateColumn|Predicate $left, PredicateColumn|Predicate $right): static
    {
        $this->result = \PyCore::import('pyorc.predicates')->Predicate(2, $left(), $right());
        return $this;
    }
}
